
# Laporan Proyek Mental Health Predictor

## 1. Pendahuluan
Aplikasi Mental Health Predictor bertujuan untuk membantu memprediksi risiko kesehatan mental pada individu berdasarkan data yang diberikan. Masalah kesehatan yang ingin dipecahkan adalah deteksi dini risiko gangguan kesehatan mental, sehingga intervensi dapat dilakukan lebih cepat dan tepat. Pendekatan yang diambil dalam pengembangan aplikasi ini adalah dengan memanfaatkan machine learning untuk menganalisis data dan memberikan prediksi, serta mengembangkan aplikasi berbasis web dan CLI agar mudah diakses oleh pengguna dan tenaga kesehatan.

## 2. Metodologi
### a. Langkah-langkah Pengembangan
1. Studi literatur terkait kesehatan mental dan machine learning.
2. Pengumpulan dan analisis dataset kesehatan mental.
3. Preprocessing data: pembersihan, normalisasi, dan transformasi fitur.
4. Pemilihan dan pelatihan model machine learning (Random Forest).
5. Evaluasi model menggunakan metrik akurasi, precision, recall, dan f1-score.
6. Pengembangan aplikasi (CLI, Flask, Gradio, Laravel) untuk integrasi model.
7. Pengujian aplikasi dan model.
8. Deployment dan dokumentasi.

### b. Pemilihan Model Machine Learning
Model Random Forest dipilih karena kemampuannya dalam menangani data dengan banyak fitur dan memberikan hasil prediksi yang stabil. Model ini juga mudah diinterpretasikan dan memiliki performa yang baik pada data klasifikasi.

Pemodelan dilakukan menggunakan Jupyter Notebook (`random_forest_model.ipynb`). Setelah model dilatih dan dievaluasi, model disimpan dalam format `.pkl` (pickle) menggunakan script `tran.py`. File `.pkl` ini kemudian digunakan untuk integrasi ke aplikasi.

### c. Preprocessing Data Medis
Preprocessing meliputi:
- Menghapus data duplikat dan data kosong
- Normalisasi nilai numerik
- Encoding fitur kategorikal
- Pembagian data menjadi data latih dan data uji

### d. Penerapan Metodologi Agile
Pengembangan aplikasi dilakukan secara iteratif dengan pembagian tugas dalam sprint mingguan. Setiap sprint menghasilkan fitur yang dapat diuji dan dievaluasi, sehingga pengembangan lebih adaptif terhadap perubahan kebutuhan.

## 3. Analisis dan Pembahasan
### a. Hasil Pengembangan
Aplikasi berhasil memprediksi risiko kesehatan mental dengan akurasi yang baik. Model Random Forest menunjukkan performa yang stabil pada data uji. Aplikasi dapat diakses melalui antarmuka CLI, web (Flask/Gradio), dan Laravel untuk integrasi lebih lanjut.

### b. Tantangan yang Dihadapi
- Ketersediaan dan kualitas data kesehatan mental yang terbatas
- Penyesuaian preprocessing untuk data yang tidak konsisten
- Integrasi model machine learning ke berbagai platform aplikasi
- Menjaga keamanan data pengguna

### c. Solusi untuk Masalah Keamanan, Optimasi, dan Integrasi
- Implementasi enkripsi data pada penyimpanan dan transmisi
- Penggunaan audit log untuk memantau akses data
- Optimasi model dengan hyperparameter tuning
- Modularisasi kode untuk memudahkan integrasi ke berbagai platform

## 4. Kesimpulan
Aplikasi Mental Health Predictor telah berhasil dikembangkan dan mampu memberikan prediksi risiko kesehatan mental secara otomatis. Model machine learning yang digunakan memberikan hasil yang memuaskan. Tantangan utama berupa data dan integrasi dapat diatasi dengan solusi yang diterapkan. Untuk pengembangan selanjutnya, disarankan untuk memperluas dataset, menambah fitur prediksi, dan meningkatkan keamanan serta user experience aplikasi.
# 🧠 Mental Health Predictor


An advanced AI-powered mental health assessment tool with **three beautiful web interfaces**: Gradio (modern blocks UI), Flask (Bootstrap 5), and CLI (interactive terminal). This project uses machine learning to predict mental health risk levels and provide personalized recommendations.

## 🏥 Kelebihan Sistem

- **Mudah digunakan oleh tenaga medis**: Antarmuka dirancang agar intuitif dan ramah pengguna untuk mendukung alur kerja tenaga kesehatan.
- **Aman**: Sistem menerapkan prinsip keamanan data dan privasi pasien.
- **Dapat diskalakan**: Arsitektur aplikasi memungkinkan pengembangan dan penambahan fitur di masa depan.
- **Dapat diintegrasikan dengan sistem rumah sakit yang ada**: Mendukung integrasi dengan sistem informasi rumah sakit melalui API atau penyesuaian lebih lanjut.

## ✨ Features


🎯 **Three Interface Options:**
- **Gradio Web App** - Modern, interactive UI with tabs, charts, and custom styling
- **Flask Web App** - Professional Bootstrap 5 interface with REST API
- **CLI App** - Beautiful terminal interface with colors and interactive prompts

🩺 **Healthcare-Ready:**
- Mudah digunakan oleh tenaga medis dengan antarmuka yang intuitif
- Fitur keamanan data pasien dan audit log
- Dukungan integrasi dengan sistem rumah sakit (API, interoperabilitas data)
- Skalabilitas untuk penggunaan di berbagai skala fasilitas kesehatan

🤖 **AI-Powered:**
- Multiple ML algorithms (Random Forest, Logistic Regression, XGBoost)
- Confidence scores and probability distributions
- Real-time predictions

📊 **Comprehensive Analysis:**
- 8 input features (age, stress, anxiety, depression, etc.)
- Detailed risk assessment (Low/Moderate/High)
- Personalized recommendations
- Interactive visualizations

🔒 **Privacy-Focused:**
- All processing done locally
- No data storage
- Anonymous and confidential

## 📁 Project Structure

```
mental_health_predictor/
├── app/
│   ├── gradio_app.py          # 🎨 Modern Gradio UI with tabs & styling
│   ├── flask_app.py           # 🌐 Flask web app + REST API
│   └── cli_app.py             # 💻 Interactive CLI with colors
├── data/
│   └── data.csv               # 📊 Sample dataset (100 records)
├── src/
│   ├── __init__.py           
│   ├── utils.py               # 🛠️ Helper functions
│   ├── data_preprocess.py     # 🔄 Data preprocessing
│   ├── model_train.py         # 🎓 Model training pipeline
│   └── predictor.py           # 🔮 Prediction module
├── templates/
│   └── index.html             # 🎨 Beautiful Bootstrap 5 UI
├── models/                    # 💾 Trained models (auto-generated)
├── logs/                      # 📝 Application logs
├── results/                   # 📈 Metrics & visualizations
├── config.yaml                # ⚙️ Configuration
├── requirements.txt           # 📦 Dependencies
├── .env                       # 🔐 Environment variables
├── .gitignore
└── README.md
```

## 🚀 Quick Start

### 📦 Installation

1. **Clone or download this repository**
```powershell
cd C:\Users\putri\mental_health_predictor
```

2. **Create and activate virtual environment**
```powershell
python -m venv venv
venv\Scripts\activate
```

3. **Install dependencies**
```powershell
pip install -r requirements.txt
```

