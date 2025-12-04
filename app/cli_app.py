"""
Command Line Interface for Mental Health Predictor
Interactive terminal-based application
"""

import sys
import os
from pathlib import Path

# Add parent directory to path
sys.path.append(str(Path(__file__).parent.parent))

from src.utils import load_config, setup_logging
from src.predictor import MentalHealthPredictor
import logging

logger = logging.getLogger(__name__)


class Colors:
    """ANSI color codes for terminal output"""
    HEADER = '\033[95m'
    BLUE = '\033[94m'
    CYAN = '\033[96m'
    GREEN = '\033[92m'
    YELLOW = '\033[93m'
    RED = '\033[91m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'
    END = '\033[0m'


class CLI:
    """Command Line Interface for mental health prediction"""
    
    def __init__(self):
        """Initialize CLI application"""
        self.config = load_config()
        setup_logging(
            log_dir=self.config['logging']['log_dir'],
            level=self.config['logging']['level']
        )
        
        self.predictor = MentalHealthPredictor(self.config)
        self.model_loaded = False
        
        try:
            self.predictor.initialize()
            self.model_loaded = True
            logger.info("CLI initialized with trained model")
        except Exception as e:
            logger.warning(f"Running in demo mode: {str(e)}")
            self.model_loaded = False
    
    def print_header(self):
        """Print application header"""
        print("\n" + "=" * 70)
        print(f"{Colors.BOLD}{Colors.CYAN}🧠 MENTAL HEALTH PREDICTOR - CLI{Colors.END}")
        print("=" * 70)
        if not self.model_loaded:
            print(f"{Colors.YELLOW}⚠️  Running in demo mode (model not trained){Colors.END}")
        print()
    
    def print_section(self, title):
        """Print section header"""
        print(f"\n{Colors.BOLD}{Colors.BLUE}{title}{Colors.END}")
        print("-" * len(title))
    
    def get_input(self, prompt, input_type=str, min_val=None, max_val=None, choices=None):
        """Get validated user input"""
        while True:
            try:
                value = input(f"{Colors.CYAN}{prompt}{Colors.END}: ").strip()
                
                if input_type == int:
                    value = int(value)
                elif input_type == float:
                    value = float(value)
                
                if choices and value not in choices:
                    print(f"{Colors.RED}❌ Invalid choice. Please choose from: {choices}{Colors.END}")
                    continue
                
                if min_val is not None and value < min_val:
                    print(f"{Colors.RED}❌ Value must be at least {min_val}{Colors.END}")
                    continue
                
                if max_val is not None and value > max_val:
                    print(f"{Colors.RED}❌ Value must be at most {max_val}{Colors.END}")
                    continue
                
                return value
                
            except ValueError:
                print(f"{Colors.RED}❌ Invalid input. Please try again.{Colors.END}")
            except KeyboardInterrupt:
                print(f"\n{Colors.YELLOW}Operation cancelled.{Colors.END}")
                return None
    
    def collect_data(self):
        """Collect assessment data from user"""
        data = {}
        
        self.print_section("👤 Personal Information")
        data['age'] = self.get_input("Age (18-80)", int, min_val=18, max_val=80)
        if data['age'] is None:
            return None
        
        self.print_section("😰 Mental Health Indicators")
        print("Rate the following from 0 (none) to 10 (severe):\n")
        
        data['stress_level'] = self.get_input("Stress Level (0-10)", int, min_val=0, max_val=10)
        if data['stress_level'] is None:
            return None
        
        data['anxiety_level'] = self.get_input("Anxiety Level (0-10)", int, min_val=0, max_val=10)
        if data['anxiety_level'] is None:
            return None
        
        data['depression_score'] = self.get_input("Depression Score (0-10)", int, min_val=0, max_val=10)
        if data['depression_score'] is None:
            return None
        
        self.print_section("📋 Medical History")
        history = self.get_input("Previous mental health issues? (yes/no)", str, 
                                 choices=['yes', 'no', 'y', 'n'])
        if history is None:
            return None
        data['mental_history'] = 1 if history.lower() in ['yes', 'y'] else 0
        
        self.print_section("🏃 Lifestyle Factors")
        data['sleep_hours'] = self.get_input("Average sleep hours per night (0-12)", float, 
                                             min_val=0, max_val=12)
        if data['sleep_hours'] is None:
            return None
        
        print(f"\n{Colors.CYAN}Exercise Frequency:{Colors.END}")
        print("  0 = Low (< 1x per week)")
        print("  1 = Medium (2-3x per week)")
        print("  2 = High (4+ per week)")
        data['exercise_frequency'] = self.get_input("Choose (0-2)", int, min_val=0, max_val=2)
        if data['exercise_frequency'] is None:
            return None
        
        support = self.get_input("Strong social support? (yes/no)", str, 
                                choices=['yes', 'no', 'y', 'n'])
        if support is None:
            return None
        data['social_support'] = 1 if support.lower() in ['yes', 'y'] else 0
        
        return data
    
    def make_prediction(self, data):
        """Make prediction and return results"""
        try:
            if self.model_loaded:
                prediction, confidence, probabilities = self.predictor.predict_with_confidence(data)
            else:
                prediction, confidence, probabilities = self.demo_prediction(data)
            
            return prediction, confidence, probabilities
            
        except Exception as e:
            logger.error(f"Prediction error: {str(e)}")
            return None, None, None
    
    def demo_prediction(self, data):
        """Demo prediction when model is not available"""
        risk_score = (data['stress_level'] + data['anxiety_level'] + data['depression_score']) / 30
        
        if risk_score > 0.7:
            prediction = "High Risk"
            confidence = 0.85
        elif risk_score > 0.4:
            prediction = "Moderate Risk"
            confidence = 0.78
        else:
            prediction = "Low Risk"
            confidence = 0.82
        
        probabilities = {
            "Low Risk": max(0, 1 - risk_score),
            "Moderate Risk": 0.3 if risk_score > 0.4 else 0.2,
            "High Risk": max(0, risk_score - 0.3)
        }
        
        return prediction, confidence, probabilities
    
    def display_results(self, prediction, confidence, probabilities, data):
        """Display prediction results"""
        print("\n" + "=" * 70)
        self.print_section("🔍 ASSESSMENT RESULTS")
        
        # Risk level indicator
        if "High" in prediction:
            color = Colors.RED
            icon = "🔴"
            message = "High Risk - Seek Professional Help"
        elif "Moderate" in prediction:
            color = Colors.YELLOW
            icon = "🟡"
            message = "Moderate Risk - Monitor Closely"
        else:
            color = Colors.GREEN
            icon = "🟢"
            message = "Low Risk - Maintain Healthy Habits"
        
        print(f"\n{color}{Colors.BOLD}{icon} {message}{Colors.END}")
        print(f"\n{Colors.BOLD}Prediction:{Colors.END} {color}{prediction}{Colors.END}")
        print(f"{Colors.BOLD}Confidence:{Colors.END} {confidence:.1%}")
        
        # Probabilities
        if probabilities:
            self.print_section("📊 Probability Distribution")
            for class_label, prob in probabilities.items():
                bar_length = int(prob * 40)
                bar = "█" * bar_length + "░" * (40 - bar_length)
                print(f"{class_label:15} {bar} {prob:.1%}")
        
        # Recommendations
        self.print_section("💡 Personalized Recommendations")
        recommendations = self.get_recommendations(prediction, data)
        for i, rec in enumerate(recommendations, 1):
            print(f"{Colors.GREEN}  {i}. {rec}{Colors.END}")
        
        # Important note
        print(f"\n{Colors.YELLOW}{Colors.BOLD}⚠️  IMPORTANT NOTE:{Colors.END}")
        print("This is an AI prediction tool and should not replace professional")
        print("medical advice. If you're experiencing mental health issues,")
        print("please consult a licensed mental health professional.")
        
        # Crisis resources
        self.print_section("🆘 Crisis Resources")
        print(f"{Colors.BOLD}National Suicide Prevention Lifeline:{Colors.END} 1-800-273-8255")
        print(f"{Colors.BOLD}Crisis Text Line:{Colors.END} Text HOME to 741741")
        print(f"{Colors.BOLD}International:{Colors.END} https://www.iasp.info/resources/Crisis_Centres/")
        
        print("\n" + "=" * 70 + "\n")
    
    def get_recommendations(self, prediction, data):
        """Get personalized recommendations"""
        recommendations = []
        
        if "High" in prediction:
            recommendations = [
                "Consider consulting a mental health professional immediately",
                "Reach out to trusted friends or family members",
                "Practice stress-reduction techniques (meditation, deep breathing)",
                "Ensure adequate sleep (7-9 hours per night)",
                "Engage in regular physical activity"
            ]
        elif "Moderate" in prediction:
            recommendations = [
                "Monitor your mental health regularly",
                "Maintain healthy sleep patterns",
                "Practice mindfulness or meditation daily",
                "Stay connected with supportive people",
                "Consider speaking with a counselor"
            ]
        else:
            recommendations = [
                "Continue maintaining healthy lifestyle habits",
                "Stay socially connected",
                "Practice regular self-care",
                "Monitor for any changes",
                "Help others who may be struggling"
            ]
        
        # Add specific recommendations
        if data.get('sleep_hours', 7) < 6:
            recommendations.insert(1, "Prioritize getting more sleep (aim for 7-9 hours)")
        
        if data.get('exercise_frequency', 1) == 0:
            recommendations.insert(2, "Increase physical activity gradually")
        
        if data.get('social_support', 1) == 0:
            recommendations.insert(1, "Build a support network through groups or activities")
        
        return recommendations
    
    def run(self):
        """Run the CLI application"""
        self.print_header()
        
        while True:
            print(f"{Colors.BOLD}What would you like to do?{Colors.END}")
            print("  1. Take mental health assessment")
            print("  2. View information")
            print("  3. Exit")
            
            choice = self.get_input("\nChoice (1-3)", int, choices=[1, 2, 3])
            
            if choice is None or choice == 3:
                print(f"\n{Colors.CYAN}Thank you for using Mental Health Predictor!{Colors.END}")
                print("Take care of your mental health. 💙\n")
                break
            
            elif choice == 1:
                print(f"\n{Colors.BOLD}Please answer the following questions honestly:{Colors.END}")
                data = self.collect_data()
                
                if data is None:
                    continue
                
                print(f"\n{Colors.CYAN}Analyzing your data...{Colors.END}")
                prediction, confidence, probabilities = self.make_prediction(data)
                
                if prediction is not None:
                    self.display_results(prediction, confidence, probabilities, data)
                else:
                    print(f"{Colors.RED}❌ Error making prediction. Please try again.{Colors.END}")
            
            elif choice == 2:
                self.print_section("ℹ️  About Mental Health Predictor")
                print("This tool uses machine learning to assess mental health risk")
                print("based on various factors. It provides educational insights")
                print("but should NOT replace professional diagnosis.\n")
                
                print(f"{Colors.BOLD}Features:{Colors.END}")
                print("  • AI-powered risk assessment")
                print("  • Personalized recommendations")
                print("  • Privacy-focused (data not stored)")
                print("  • Educational and informative\n")
                
                input(f"{Colors.CYAN}Press Enter to continue...{Colors.END}")


def main():
    """Main entry point"""
    try:
        cli = CLI()
        cli.run()
    except KeyboardInterrupt:
        print(f"\n\n{Colors.YELLOW}Application terminated by user.{Colors.END}\n")
        sys.exit(0)
    except Exception as e:
        print(f"\n{Colors.RED}❌ Fatal error: {str(e)}{Colors.END}\n")
        sys.exit(1)


if __name__ == "__main__":
    main()
