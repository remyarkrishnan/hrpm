<?php

return [
    'create' => [
        'title' => 'Create Shift - :company',
        'page_title' => 'Create Shift',
        'back_to_shifts' => 'Back to Shifts',
        'description' => 'Set up a new shift schedule for construction teams',

        'shift_information' => 'Shift Information',
        'shift_name' => 'Shift Name',
        'placeholder_shift_name' => 'Example: Morning Construction Shift',
        'shift_type' => 'Shift Type',
        'select_shift_type' => 'Select shift type',
        'type_morning' => 'Morning Shift',
        'type_evening' => 'Evening Shift',
        'type_night' => 'Night Shift',
        'type_flexible' => 'Flexible Shift',

        'shift_timing' => 'Shift Timing',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'break_duration' => 'Break Duration',
        'placeholder_break' => 'Example: 60',
        'total_working_hours' => 'Total Working Hours',

        'location_assignment' => 'Location & Assignment',
        'work_location' => 'Work Location',
        'select_location' => 'Select location',
        'max_employees' => 'Max Employees',
        'placeholder_max_employees' => 'Example: 25',
        'supervisor' => 'Supervisor',
        'select_supervisor' => 'Select supervisor',
        'associated_project' => 'Associated Project',
        'select_project_optional' => 'Select project (optional)',

        'shift_configuration' => 'Shift Configuration',
        'working_days' => 'Working Days',
        'overtime_settings' => 'Overtime Settings',
        'overtime_allowed' => 'Overtime Allowed',
        'no_overtime' => 'No Overtime',
        'shift_description' => 'Shift Description',

        'preview' => 'Shift Preview',
        'preview_new_shift' => 'New Shift',
        'preview_type_default' => 'Morning',
        'preview_time_default' => 'Set start & end times',
        'preview_location_none' => 'No location selected',
        'preview_capacity_none' => 'No capacity set',

        'create_shift' => 'Create Shift',
        'cancel' => 'Cancel',
    ],
    'edit' => [
        'title' => 'Edit Shift - :company',
        'page_title' => 'Edit Shift',
        'back_to_shifts' => 'Back to Shifts',
        'view_details' => 'View Details',
        'update_note' => 'Update the configuration for :name shift',

        'update_shift_information' => 'Update Shift Information',
        'update_shift_timing' => 'Update Shift Timing',
        'location_assignment_updates' => 'Location & Assignment Updates',

        'current_employee_assignments' => 'Current Employee Assignments',
        'manage_assignments' => 'Manage Assignments',
        'add_employees' => 'Add Employees',

        'audit_recent_changes' => 'Recent Changes',

        'update_shift' => 'Update Shift',
        'cancel_changes' => 'Cancel Changes',

        'delete_shift_confirm' => 'Are you sure you want to delete this shift? This action cannot be undone and will remove all employee assignments.',
        'delete_success' => 'Shift deleted successfully',
        'delete_error' => 'Error deleting shift',
    ],
'index' => [
        'title' => 'Shift Management - :company',
        'page_title' => 'Shift Management',
        'description' => 'Manage all shift schedules, employee assignments, and capacity planning across construction sites',
        'create_shift' => 'Create Shift',

        'stats_morning' => 'Morning Shift Workers',
        'stats_evening' => 'Evening Shift Workers',
        'stats_night' => 'Night Shift Workers',
        'stats_total' => 'Total Active Shifts',
        'stats_total_note' => 'All shifts combined',

        'current_schedule' => 'Current Shift Schedule',

        'table' => [
            'shift_name' => 'Shift Name',
            'type' => 'Type',
            'time' => 'Time',
            'location' => 'Location',
            'employees' => 'Employees',
            'status' => 'Status',
            'actions' => 'Actions',
        ],

        'multiple_sites' => 'Multiple Sites',
        'workers' => 'workers',
        'status_active' => 'Active',
        'no_shifts' => 'No shifts configured yet',

        'weekly_schedule' => 'Weekly Shift Schedule',

        'days' => [
            'mon' => 'Mon',
            'tue' => 'Tue',
            'wed' => 'Wed',
            'thu' => 'Thu',
            'fri' => 'Fri',
            'sat' => 'Sat',
            'sun' => 'Sun',
        ],

        'assign_alert' => 'Employee assignment functionality will be implemented',
    ],
    'show' => [
        'title' => 'Shift Details - :company',
        'page_title' => 'Shift Details',
        'back_to_shifts' => 'Back to Shifts',
        'default_name' => 'Morning Shift',
        'default_location' => 'Site A - Gurgaon',

        'labels' => [
            'currently_assigned' => 'currently assigned',
            'working_hours' => 'hours',
            'shift_name' => 'Shift Name',
            'type' => 'Type',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'break_duration' => 'Break Duration',
            'minutes' => 'minutes',
            'hours' => 'hours',
            'work_location' => 'Work Location',
            'supervisor' => 'Supervisor',
            'project' => 'Project',
            'max_capacity' => 'Max Capacity',
            'employees' => 'employees',
            'overtime_allowed' => 'Overtime Allowed',
        ],

        'section' => [
            'shift_information' => 'Shift',
            'location_assignment' => 'Location & Assignment',
            'working_days_schedule' => 'Working Days Schedule',
            'assigned_employees' => 'Assigned Employees',
            'shift_description' => 'Shift Description',
        ],

        'capacity' => 'Capacity',
        'yes' => 'Yes',
        'no' => 'No',

        'days' => [
            'working' => 'Working',
            'off' => 'Off',
        ],

        'assign_more' => 'Assign More',
        'view_more_count' => 'View +:count more',
        'view_all_hint' => 'View all employees',

        'duplicate' => 'Duplicate Shift',
        'activate' => 'Activate Shift',
        'delete' => 'Delete Shift',

        'assign_alert' => 'Employee assignment functionality will be implemented',
        'remove_employee_confirm' => 'Are you sure you want to remove this employee from the shift?',
        'remove_employee_alert' => 'Employee removed from shift',
        'view_all' => 'View all employees functionality will be implemented',
        'duplicate_confirm' => 'Create a duplicate of this shift with same configuration?',
        'duplicate_alert' => 'Shift duplicated successfully',
        'activate_alert' => 'Shift activation functionality will be implemented',
        'delete_confirm' => 'Are you sure you want to delete this shift? This will remove all assignments.',
        'delete_success' => 'Shift deleted successfully',
        'delete_error' => 'Error deleting shift',
    ],
];
