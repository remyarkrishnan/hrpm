<?php
return [
    'create_title' => 'Create New Construction Project',
    'create_subtitle' => 'Add a new project with 12-step approval workflow',
    'edit_title' => 'Edit Project',
    'edit_subtitle' => 'Update project information and settings',
    'sections' => [
        'info' => 'Project Information',
        'client' => 'Client Information',
        'planning' => 'Project Planning',
        'documents' => 'Project Documents',
        'current_docs' => 'Current Documents',
        'upload_docs' => 'Upload Additional Documents',
        'workflow' => 'Approval Workflow',
    ],
    'placeholders' => [
        'name' => 'e.g. Residential Complex Phase 2',
        'code' => 'e.g. PROJ-RES-2024-001',
        'desc' => 'Detailed project description, scope, and objectives',
        'location' => 'e.g. Sector 15, Gurgaon',
        'client_name' => 'e.g. ABC Developers Pvt Ltd',
        'client_contact' => 'Phone/Email/Contact Person',
        'budget' => 'e.g. 5000000',
    ],
    'options' => [
        'select_type' => 'Select Project Type',
        'select_priority' => 'Select Priority',
        'select_manager' => 'Select Project Manager',
    ],
    'labels' => [
        'upload_optional' => 'Upload Documents (Optional)',
        'upload_new' => 'Upload New Documents (Optional)',
        'percent' => 'Progress Percentage',
    ],
    'help' => [
        'formats' => 'Accepted formats: PDF, DOC, DOCX, JPG, PNG. Max 5MB per file.',
    ],
    'workflow_info' => 'This project will automatically generate a 12-step approval workflow with the following consultancy stages:',
    'steps' => [
        '1' => '1. Design Review',
        '2' => '2. Environmental Assessment',
        '3' => '3. Safety Planning',
        '4' => '4. Structural Analysis',
        '5' => '5. Electrical Planning',
        '6' => '6. Plumbing & HVAC',
        '7' => '7. Financial Approval',
        '8' => '8. Legal Compliance',
        '9' => '9. Municipal Permits',
        '10' => '10. Fire Safety Clearance',
        '11' => '11. Quality Assurance',
        '12' => '12. Final Approval',
    ]
];