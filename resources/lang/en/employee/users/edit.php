<?php
return [
    'title' => 'Edit Employee',
    'description' => 'Update employee information for :company',
    'sections' => [
        'basic' => 'Basic Information',
        'personal' => 'Personal Information (Optional)',
        'employment' => 'Employment Information',
    ],
    'fields' => [
        'new_password' => 'New Password',
        'password_placeholder' => 'Leave blank to keep current password',
        'confirm_password' => 'Confirm New Password',
        'emergency_contact' => 'Emergency Contact Name',
        'emergency_phone' => 'Emergency Phone',
    ],
    'buttons' => [
        'update' => 'Update Employee',
        'delete' => 'Delete Employee',
        'updating' => 'Updating Employee...',
    ],
    'messages' => [
        'delete_confirm' => 'Are you sure you want to delete this employee?\n\nThis action cannot be undone and will permanently remove:\n• Employee profile and personal information\n• All associated records\n• Login access to the system\n\nType "DELETE" to confirm this action.',
        'delete_prompt' => 'To confirm deletion, type "DELETE" (in capital letters):',
        'delete_cancel' => 'Deletion cancelled. You must type "DELETE" exactly as shown.',
        'delete_success' => 'Employee deleted successfully',
        'delete_failed' => 'Failed to delete employee',
        'password_length' => 'Password must be at least 6 characters long',
    ]
];