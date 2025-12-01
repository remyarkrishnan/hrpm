<?php
return [
    'title' => 'Create New Shift',
    'subtitle' => 'Set up a new shift schedule for construction teams',
    'sections' => [
        'info' => 'Shift Information',
        'timing' => 'Shift Timing',
        'location' => 'Location & Assignment',
        'config' => 'Shift Configuration',
        'preview' => 'Shift Preview',
    ],
    'placeholders' => [
        'name' => 'e.g. Morning Construction Shift',
        'break' => '60',
        'max' => 'e.g. 25',
        'desc' => 'Describe the shift duties, requirements, or special instructions',
    ],
    'options' => [
        'select_type' => 'Select Shift Type',
        'select_location' => 'Select Location',
        'select_supervisor' => 'Select Supervisor',
        'select_project' => 'Select Project (Optional)',
        'overtime_yes' => 'Overtime Allowed',
        'overtime_no' => 'No Overtime',
    ],
    'preview' => [
        'new' => 'New Shift',
        'no_loc' => 'No location selected',
        'no_limit' => 'No limit set',
    ]
];