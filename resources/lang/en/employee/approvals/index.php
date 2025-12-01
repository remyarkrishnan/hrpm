<?php
// resources/lang/ar/employee/approvals/index.php

return [
    'title' => 'موافقات المشاريع',
    'page_title' => 'موافقات المشاريع',
    'header_title' => 'موافقات المشاريع',
    'header_description' => 'إدارة سير عمل الموافقة ذات الـ12 خطوة لمشاريع البناء',
    
    'filters' => [
        'all_projects' => 'جميع المشاريع',
        'residential' => 'سكنية',
        'commercial' => 'تجارية',
        'infrastructure' => 'بنية تحتية',
    ],
    
    'stats' => [
        'pending_approvals' => 'الموافقات المعلقة',
        'pending_description' => 'عبر جميع المشاريع',
        'approved_steps' => 'الخطوات المعتمدة',
        'approved_description' => 'هذا الشهر',
        'in_progress' => 'قيد التقدم',
        'in_progress_description' => 'قيد المراجعة حالياً',
        'active_projects' => 'المشاريع النشطة',
        'active_projects_description' => 'تتطلب موافقات',
    ],
    
    'workflow' => [
        'title' => 'عملية الموافقة القياسية ذات الـ12 خطوة',
        'steps' => [
            1 => ['title' => 'مراجعة التصميم', 'description' => 'التحقق من خطط العمارة'],
            2 => ['title' => 'تقييم البيئة', 'description' => 'دراسة التأثير البيئي'],
            3 => ['title' => 'تخطيط السلامة', 'description' => 'موافقة بروتوكولات السلامة'],
            4 => ['title' => 'تحليل الهيكل', 'description' => 'مراجعة حسابات الهندسة'],
            5 => ['title' => 'تخطيط الكهرباء', 'description' => 'تصميم أنظمة الكهرباء'],
            6 => ['title' => 'السباكة وتكييف الهواء', 'description' => 'موافقة أنظمة MEP'],
            7 => ['title' => 'الموافقة المالية', 'description' => 'موافقة الميزانية والتكاليف'],
            8 => ['title' => 'الامتثال القانوني', 'description' => 'فحص المتطلبات القانونية'],
            9 => ['title' => 'التصاريح البلدية', 'description' => 'موافقات الحكومة'],
            10 => ['title' => 'تصريح السلامة من الحريق', 'description' => 'موافقة إدارة الحرائق'],
            11 => ['title' => 'ضمان الجودة', 'description' => 'التحقق من معايير الجودة'],
            12 => ['title' => 'الموافقة النهائية', 'description' => 'تصريح بدء المشروع'],
        ],
    ],
    
    'table' => [
        'title' => 'طلبات الموافقة الحالية',
        'columns' => [
            'project' => 'المشروع',
            'current_step' => 'الخطوة الحالية',
            'consultancy_type' => 'نوع الاستشارات',
            'due_date' => 'تاريخ الاستحقاق',
            'status' => 'الحالة',
            'responsible' => 'المسؤول',
            'actions' => 'الإجراءات',
        ],
        'step' => 'خطوة',
        'no_requests' => 'لم يتم العثور على طلبات موافقة',
    ],
    
    'js' => [
        'approve_prompt' => 'إضافة تعليقات الموافقة (اختياري):',
        'approve_success' => 'تمت الموافقة على خطوة الموافقة بنجاح',
        'approve_error' => 'خطأ في الموافقة على الخطوة',
        'reject_prompt' => 'يرجى إدخال سبب الرفض:',
        'reject_required' => 'سبب الرفض مطلوب',
        'reject_success' => 'تم رفض خطوة الموافقة',
        'reject_error' => 'خطأ في رفض الخطوة',
    ],
];
