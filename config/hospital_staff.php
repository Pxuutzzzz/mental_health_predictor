<?php
/**
 * Hospital Staff Mode Configuration
 * Enables RS staff to perform assessments on behalf of patients during consultations
 */
return [
    'enabled' => true,
    
    // Staff authentication
    'staff_roles' => [
        'doctor',
        'psychiatrist',
        'psychologist',
        'nurse',
        'counselor'
    ],
    
    // Access control
    'require_staff_login' => false, // Set true in production
    'allow_anonymous_staff' => true, // For demo/testing
    
    // Patient data
    'require_patient_id' => false, // Set true if RS requires MRN
    'allow_patient_lookup' => true, // Enable patient search by MRN
    
    // Workflow
    'auto_save_to_facility' => true, // Auto-save to RS database
    'skip_external_integration' => true, // Don't send to another RS (already at RS)
    'generate_instant_report' => true, // Generate PDF/print-ready report
    
    // UI Settings
    'show_clinical_fields' => true, // Show ICD codes, clinical notes, etc.
    'enable_quick_assessment' => true, // Simplified form for busy clinics
    
    // Facilities that support staff mode
    'supported_facilities' => [
        'rs_hermina',
        'rsud_jakarta',
        'rs_siloam'
    ],
    
    // Staff access codes (for demo - replace with proper auth)
    'demo_access_codes' => [
        'STAFF-HERMINA-2026' => 'rs_hermina',
        'STAFF-RSUD-2026' => 'rsud_jakarta',
        'STAFF-SILOAM-2026' => 'rs_siloam'
    ]
];
