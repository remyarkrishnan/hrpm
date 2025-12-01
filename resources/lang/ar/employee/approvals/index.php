<?php
// resources/lang/ar/employee/approvals/index.php
return [
    'title' => 'Project Approvals',
    'page_title' => 'Project Approvals',
    'header_title' => 'Project Approvals',
    'header_description' => 'Manage 12-step approval workflow for construction projects',
    
    'filters' => [
        'all_projects' => 'All Projects',
        'residential' => 'Residential',
        'commercial' => 'Commercial',
        'infrastructure' => 'Infrastructure',
    ],
    
    'stats' => [
        'pending_approvals' => 'Pending Approvals',
        'pending_description' => 'Across all projects',
        'approved_steps' => 'Approved Steps',
        'approved_description' => 'This month',
        'in_progress' => 'In Progress',
        'in_progress_description' => 'Currently reviewing',
        'active_projects' => 'Active Projects',
        'active_projects_description' => 'Requiring approvals',
    ],
    
    'workflow' => [
        'title' => 'Standard 12-Step Approval Process',
        'steps' => [
            1 => ['title' => 'Design Review', 'description' => 'Architectural plans verification'],
            2 => ['title' => 'Environmental Assessment', 'description' => 'Environmental impact study'],
            3 => ['title' => 'Safety Planning', 'description' => 'Safety protocols approval'],
            4 => ['title' => 'Structural Analysis', 'description' => 'Engineering calculations review'],
            5 => ['title' => 'Electrical Planning', 'description' => 'Electrical systems design'],
            6 => ['title' => 'Plumbing & HVAC', 'description' => 'MEP systems approval'],
            7 => ['title' => 'Financial Approval', 'description' => 'Budget and cost approval'],
            8 => ['title' => 'Legal Compliance', 'description' => 'Legal requirements check'],
            9 => ['title' => 'Municipal Permits', 'description' => 'Government approvals'],
            10 => ['title' => 'Fire Safety Clearance', 'description' => 'Fire department approval'],
            11 => ['title' => 'Quality Assurance', 'description' => 'QA standards verification'],
            12 => ['title' => 'Final Approval', 'description' => 'Project commencement clearance'],
        ],
    ],
    
    'table' => [
        'title' => 'Current Approval Requests',
        'columns' => [
            'project' => 'Project',
            'current_step' => 'Current Step',
            'consultancy_type' => 'Consultancy Type',
            'due_date' => 'Due Date',
            'status' => 'Status',
            'responsible' => 'Responsible',
            'actions' => 'Actions',
        ],
        'step' => 'Step',
        'no_requests' => 'No approval requests found',
    ],
    
    'js' => [
        'approve_prompt' => 'Add approval remarks (optional):',
        'approve_success' => 'Approval step approved successfully',
        'approve_error' => 'Error approving step',
        'reject_prompt' => 'Please enter rejection reason:',
        'reject_required' => 'Rejection reason is required',
        'reject_success' => 'Approval step rejected',
        'reject_error' => 'Error rejecting step',
    ],
];
