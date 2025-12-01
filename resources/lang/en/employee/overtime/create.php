<?php
return [
    'title' => 'Request Overtime',
    'subtitle' => 'Submit overtime request for approval',
    'sections' => [
        'info' => 'Employee Information',
        'details' => 'Overtime Details',
        'reason' => 'Reason & Justification',
        'calculation' => 'Rate Calculation',
    ],
    'placeholders' => [
        'hours' => 'e.g. 2.5',
        'reason' => 'Please provide detailed reason for overtime work',
        'description' => 'Describe the specific work to be done',
        'hours_help' => 'Enter overtime hours (0.5 to 8 hours maximum)',
    ],
    'calc' => [
        'base_rate' => 'Base Hourly Rate',
        'multiplier' => 'Overtime Multiplier',
        'overtime_rate' => 'Overtime Rate',
        'estimated_total' => 'Estimated Total',
        'per_hour' => 'Per hour',
        'standard_rate' => 'Standard rate',
        'total_pay' => 'Total overtime pay',
    ]
];