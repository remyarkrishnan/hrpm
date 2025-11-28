<?php
return [
'progress' => [
    'title' => 'Project Progress - :project',
    'page_title' => 'Progress Dashboard: :project',
    'description' => 'Track timeline, completion %, and delays for this project.',
    'back_button' => 'Back to Projects',
    
    // Stats
    'overall_completion' => 'Overall Completion',
    'delayed_subplans' => 'Delayed Subplans',
    'steps_count' => 'Steps in Project',
    
    // Table
    'overview_title' => 'Project Progress Overview',
    'table' => [
        'step' => 'Step',
        'subplan' => 'Subplan',
        'progress' => 'Progress',
        'deadline' => 'Deadline',
        'status' => 'Status',
    ],
    'no_deadline' => '-',
    
    // Status badges
    'status' => [
        'completed' => 'Completed',
        'delayed' => 'Delayed',
        'in_progress' => 'In Progress',
    ],
    'no_data' => 'No steps/subplans found',
    
    // Gantt Chart
    'gantt_title' => 'Timeline (Gantt View)',
    'chart' => [
        'progress_label' => 'Progress (%)',
        'complete_percent' => '% Complete',
    ],
],
'reports' => [
    'title' => 'Project Reports - :company',
    'page_title' => 'Project Reports Dashboard',
    'description' => 'Generate and view project reports including employee allocation, step-wise progress, and approvals.',
    
    'stats' => [
        'total_employees' => 'Total Employees Allocated',
        'total_steps' => 'Total Project Steps',
        'delayed_subplans' => 'Delayed Subplans',
        'pending_approvals' => 'Pending Approvals',
    ],
    
    'tabs' => [
        'allocations' => 'Employee Allocation',
        'progress' => 'Step-wise Progress',
        'approvals' => 'Approval Status',
    ],
    
    'no_data' => '-',
    'no_deadline' => '-',
    
    'allocations' => [
        'table' => [
            'project' => 'Project',
            'employee' => 'Employee',
            'role' => 'Role',
            'allocation' => 'Allocation %',
            'remarks' => 'Remarks',
        ],
        'no_data' => 'No employee allocations found',
    ],
    
    'progress' => [
        'table' => [
            'project' => 'Project',
            'step' => 'Step',
            'subplan' => 'Subplan',
            'progress' => 'Progress %',
            'deadline' => 'Deadline',
            'status' => 'Status',
        ],
        'status' => [
            'completed' => 'Completed',
            'delayed' => 'Delayed',
            'in_progress' => 'In Progress',
        ],
        'no_data' => 'No steps found',
    ],
    
    'approvals' => [
        'table' => [
            'project' => 'Project',
            'subplan' => 'Subplan',
            'employee' => 'Employee',
            'allocation' => 'Allocation %',
            'status' => 'Approval Status',
        ],
        'status' => [
            'approved' => 'Approved',
            'pending' => 'Pending',
            'rejected' => 'Rejected',
        ],
        'no_data' => 'No approvals found',
    ],
],
'resource_allocations' => [
    'title' => 'Resource Allocation for: :activity :company',
    'page_title' => 'Resource Allocation for: :activity',
    'description' => 'View which employees are working on this subplan and their allocation %',
    
    'stats' => [
        'total_employees' => 'Total Employees Assigned',
        'active_projects' => 'Active Projects',
        'total_subplans' => 'Subplans Under Execution',
    ],
    
    'performance_summary' => 'Performance Summary',
    
    'performance' => [
        'excellent' => 'Excellent (:count)',
        'good' => 'Good (:count)',
        'average' => 'Average (:count)',
        'poor' => 'Poor (:count)',
    ],
    
    'assign_title' => 'Assign Employee to Subplan',
    
    'form' => [
        'employee_label' => 'Employee',
        'select_employee' => 'Select Employee',
        'role_label' => 'Role',
        'allocation_label' => 'Allocation %',
        'remarks_label' => 'Remarks',
        'create_button' => 'Create Allocation',
    ],
    
    'table_title' => 'Employee Resource Allocation for: :activity',
    'table' => [
        'employee' => 'Employee',
        'allocation' => 'Allocation %',
        'performance' => 'Performance',
        'actions' => 'Actions',
    ],
    
    'performance_labels' => [
        'excellent' => 'Excellent',
        'good' => 'Good',
        'average' => 'Average',
        'poor' => 'Poor',
    ],
    
    'delete_confirm' => 'Remove allocation?',
    'delete_tooltip' => 'Remove allocation',
    'no_allocations' => 'No employees assigned to this subplan yet',
    
    'chart' => [
        'excellent' => 'Excellent',
        'good' => 'Good',
        'average' => 'Average',
        'poor' => 'Poor',
    ],
],
'superplans' => [
    'create' => [
        'title' => 'Create Subplan for Step: :step :company',
        'page_title' => 'Create Subplan for Step: :step',
        'description' => 'Add a new subplan',
        'back_button' => 'Back to Subplans for :step',
        
        'form' => [
            'activity_name' => 'Activity Name',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'progress_percentage' => 'Progress %',
            'create_button' => 'Create Subplan',
            'cancel_button' => 'Cancel',
        ],
    ],
    'edit' => [
        'title' => 'Edit Subplan: :activity',
        'page_title' => 'Edit Subplan: :activity',
        'back_button' => 'Back to Subplans',
        
        'form' => [
            'activity_name' => 'Activity Name',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'progress_percentage' => 'Progress %',
            'update_button' => 'Update Subplan',
            'cancel_button' => 'Cancel',
        ],
    ],
    'index' => [
        'title' => 'Sub-Plans for Step: :step :company',
        'page_title' => 'Sub-Plans',
        'step_label' => 'Step',
        'add_button' => 'Add Sub-Plan',
        'table_title' => 'Sub-Plans for Step: :step',
        'table' => [
            'index' => '#',
            'activity' => 'Activity',
            'duration' => 'Duration',
            'progress' => 'Progress',
            'description' => 'Description',
            'view' => 'View',
            'actions' => 'Actions',
        ],
        'view_resources' => 'View Project Resource Overview',
        'edit_tooltip' => 'Edit Subplan',
        'resources_tooltip' => 'View Resource Allocation',
        'delete_confirm' => 'Are you sure you want to delete this subplan? This action cannot be undone.',
        'delete_confirm_js' => 'Are you sure you want to delete this subplan? This action cannot be undone.',
        'delete_error' => 'Failed to delete subplan',
        'no_data' => 'No Sub-Plans Found',
    ],
],

'create' => [
    'title' => 'Create Project - :company',
    'page_title' => 'Create New Project',
    'back_button' => 'Back to Projects',
    'description' => 'Add a new project with 12-step approval workflow for :company',
    
    'sections' => [
        'project_info' => 'Project Information',
        'client_info' => 'Client Information',
        'project_planning' => 'Project Planning',
        'approval_workflow' => 'Approval Workflow',
    ],
    
    'form' => [
        'project_name' => 'Project Name',
        'project_name_placeholder' => 'e.g. Residential Complex Phase 2',
        'project_code' => 'Project Code',
        'project_code_placeholder' => 'e.g. PROJ-RES-2024-001',
        'priority' => 'Priority',
        'select_priority' => 'Select Priority',
        'priority_low' => 'Low',
        'priority_medium' => 'Medium',
        'priority_high' => 'High',
        'description' => 'Project Description',
        'description_placeholder' => 'Detailed project description, scope, and objectives',
        'location' => 'Location',
        'location_placeholder' => 'e.g. Sector 15, Gurgaon',
        'client_name' => 'Client Name',
        'client_name_placeholder' => 'e.g. ABC Developers Pvt Ltd',
        'client_contact' => 'Client Contact',
        'client_contact_placeholder' => 'Phone/Email/Contact Person',
        'budget' => 'Project Budget (₹)',
        'budget_placeholder' => 'e.g. 5000000',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'project_manager' => 'Project Manager',
        'select_manager' => 'Select Project Manager',
        'default_manager_title' => 'Project Manager',
        'create_button' => 'Create Project',
        'cancel_button' => 'Cancel',
        'creating' => 'Creating Project',
    ],
    
    'workflow' => [
        'process_title' => '12-Step Approval Process',
        'description' => 'This project will automatically generate a 12-step approval workflow with the following consultancy stages:',
        'steps' => [
            '1' => 'Design Consultancy',
            '2' => 'Environment Consultancy',
            '3' => 'Construction Management',
            '4' => 'Safety Consultancy',
            '5' => 'Testing & Commissioning',
            '6' => 'Finance Approval',
            '7' => 'Procurement',
            '8' => 'Quality Control',
            '9' => 'Inspection',
            '10' => 'Final Approval',
            '11' => 'Documentation',
            '12' => 'Completion',
        ],
    ],
    
    'validation' => [
        'end_date_after_start' => 'Expected completion date must be after start date',
    ],
],
'edit' => [
    'title' => 'Edit Project - :project - :company',
    'page_title' => 'Edit Project',
    'back_button' => 'Back to Projects',
    'view_button' => 'View Project',
    'description' => 'Update project information and settings',
    
    'sections' => [
        'project_info' => 'Project Information',
        'client_info' => 'Client Information',
        'project_planning' => 'Project Planning',
    ],
    
    'form' => [
        'project_name' => 'Project Name',
        'project_code' => 'Project Code',
        'priority' => 'Priority',
        'priority_low' => 'Low',
        'priority_medium' => 'Medium',
        'priority_high' => 'High',
        'description' => 'Project Description',
        'location' => 'Location',
        'client_name' => 'Client Name',
        'client_contact' => 'Client Contact',
        'budget' => 'Project Budget (₹)',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'project_manager' => 'Project Manager',
        'select_manager' => 'Select Project Manager',
        'default_manager_title' => 'Project Manager',
        'update_button' => 'Update Project',
        'cancel_button' => 'Cancel',
        'delete_button' => 'Delete Project',
        'updating' => 'Updating Project',
    ],
    
    'validation' => [
        'end_date_after_start' => 'End date must be after start date',
    ],
    
    'delete' => [
        'confirm1' => 'Are you sure you want to delete this project?\n\nThis will permanently remove:\n• Project information and documents\n• All approval steps and progress\n• Resource allocations\n\nType "DELETE" to confirm.',
        'confirm2' => 'Type "DELETE" to confirm project deletion:',
        'cancelled' => 'Deletion cancelled. You must type "DELETE" exactly.',
        'success' => 'Project deleted successfully',
        'error' => 'Failed to delete project',
    ],
],
'index' => [
    'title' => 'Project Management - :company',
    'page_title' => 'Project Management',
    'description' => 'Manage projects with 12-step approval workflow',
    'new_project' => 'New Project',
    
    'filters' => [
        'search_placeholder' => 'Search projects...',
        'priority' => 'Priority',
        'priority_low' => 'Low',
        'priority_medium' => 'Medium',
        'priority_high' => 'High',
        'all_managers' => 'All Managers',
        'filter_button' => 'Filter',
        'clear_button' => 'Clear',
    ],
    
    'location_icon' => '📍',
    'currency_symbol' => '₹',
    
    'actions' => [
        'view_details' => 'View Details',
        'edit_project' => 'Edit Project',
        'delete' => 'Delete',
    ],
    
    'meta' => [
        'type' => 'Type',
        'priority' => 'Priority',
        'status' => 'Status',
    ],
    
    'progress_label' => 'Progress',
    'not_assigned' => 'Not Assigned',
    'not_set' => 'Not set',
    'days_overdue' => 'days overdue',
    'days_left' => 'days left',
    'approved' => 'Approved',
    
    'no_projects' => 'No Projects Found',
    'no_matching_projects' => 'No projects match your filters.',
    'start_first_project' => 'Start by creating your first construction project.',
    'create_first_project' => 'Create First Project',
    
    'delete' => [
        'confirm1' => 'Are you sure you want to delete this project?\n\nThis will permanently remove:\n• Project information and documents\n• All approval steps and progress\n• Resource allocations\n\nType "DELETE" to confirm.',
        'confirm2' => 'Type "DELETE" to confirm project deletion:',
        'cancelled' => 'Deletion cancelled. You must type "DELETE" exactly.',
        'error' => 'Failed to delete project',
    ],
],
'resources_overview' => [
    'title' => 'Project Resource Overview - :project :company',
    'page_title' => 'Project Resource Overview - :project',
    'description' => 'Track how employees are allocated across each project step and subplan.',
    'back_to_projects' => 'Back to Projects',
    
    'stats' => [
        'total_steps' => 'Total Steps Across Projects',
        'total_subplans' => 'Total Subplans',
    ],
    
    'step_label' => 'Step :number: :name',
    
    'progress_label' => 'Progress',
    
    'table' => [
        'employee' => 'Employee',
        'role' => 'Role',
        'allocation' => 'Allocation %',
        'performance' => 'Performance',
    ],
    
    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'rejected' => 'Rejected',
    ],
    
    'no_assigned' => 'Not Assigned',
    'no_employees_assigned' => 'No employees assigned',
    'no_subplans' => 'No subplans defined for this step.',
    'no_steps' => 'No steps for this project yet.',
],
'show' => [
    'title' => 'Project: :project - :company',
    'page_title' => 'Project Details',
    'back_to_projects' => 'Back to Projects',
    'edit_project' => 'Edit Project',
    
    'progress_section' => 'Project Progress',
    'timeline_section' => 'Project Timeline',
    'budget_section' => 'Budget Information',
    'project_info_section' => 'Project Information',
    'team_info_section' => 'Team Information',
    'approval_workflow' => '12-Step Approval Workflow',
    'documents_section' => 'Project Documents',
    
    'view_progress' => 'View Progress',
    
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'description_label' => 'Description',
    'location_label' => 'Location',
    'client_label' => 'Client',
    'project_manager' => 'Project Manager',
    'created_by' => 'Created By',
    'created_on' => 'Created On',
    'due_date' => 'Due',
    'approved_date' => 'Approved',
    'responsible_person' => 'Responsible',
    
    'currency_symbol' => '₹',
    'total_budget' => 'Total Project Budget',
    'project_document' => 'Project Document',
    'update_step' => 'Update Step',
    'view_subplans' => 'View or Add Subplans',
    'subplans' => 'Subplans',
    
    'not_set' => 'Not set',
    'not_assigned' => 'Not assigned',
    'unknown' => 'Unknown',
    
    'priority' => [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'critical' => 'Critical',
    ],
    
    'status' => [
        'draft' => 'Draft',
        'planning' => 'Planning',
        'approval-pending' => 'Approval Pending',
        'approved' => 'Approved',
        'in-progress' => 'In Progress',
        'on-hold' => 'On Hold',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'pending' => 'Pending',
        'rejected' => 'Rejected',
    ],
],
'step_modal' => [
    'update_step_title' => 'Update Step: :step',
    'close' => 'Close',
    
    'status_label' => 'Status',
    'status' => [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'approved' => 'Approved',
    ],
    
    'responsible_label' => 'Responsible Person',
    'select_person' => '-- Select --',
    
    'due_date_label' => 'Due Date',
    
    'progress_label' => 'Progress (%)',
    
    'remarks_label' => 'Remarks',
    
    'document_label' => 'Supporting Document',
    'existing_files' => 'Existing Files',
    'view_document' => 'View Document',
    
    'close_button' => 'Close',
    'save_button' => 'Save Changes',
],


];