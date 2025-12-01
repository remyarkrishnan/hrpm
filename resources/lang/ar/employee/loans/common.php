<?php
return [
    'purposes' => [
        'medical_emergency' => 'حالة طبية طارئة',
        'personal_expenses' => 'مصاريف شخصية',
        'other' => 'أخرى',
    ],
    'durations' => [
        '6_months' => '6 أشهر',
        '9_months' => '9 أشهر',
        '12_months' => '12 شهر',
        '24_months' => '24 شهر',
    ],
    'status' => [
        'pending' => 'قيد الانتظار',
        'approved' => 'مقبول',
        'rejected' => 'مرفوض',
    ],
    'actions' => [
        'back' => 'العودة للقروض',
        'view' => 'عرض التفاصيل',
        'edit' => 'تعديل الطلب',
        'delete' => 'حذف الطلب',
        'apply' => 'طلب قرض',
        'submit' => 'إرسال طلب القرض',
        'update' => 'تحديث طلب القرض',
        'cancel' => 'إلغاء',
        'cancel_changes' => 'إلغاء التغييرات',
        'download' => 'تحميل',
        'open_new' => 'فتح في نافذة جديدة',
    ],
    'labels' => [
        'amount' => 'المبلغ',
        'loan_amount' => 'مبلغ القرض',
        'purpose' => 'الغرض',
        'duration' => 'مدة السداد',
        'applied_date' => 'تاريخ التقديم',
        'status' => 'الحالة',
        'actions' => 'الإجراءات',
        'reason' => 'سبب طلب القرض',
        'documents' => 'المستندات الداعمة',
        'additional_docs' => 'مستندات إضافية',
        'doc_help' => 'قم بتحميل قسيمة الراتب، إثبات الهوية، إلخ. الحد الأقصى 5 ميجابايت لكل ملف.',
        'supporting_doc' => 'مستند داعم',
    ],
    'messages' => [
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف طلب القرض هذا؟\n\nلا يمكن التراجع عن هذا الإجراء.',
        'delete_success' => 'تم حذف طلب القرض بنجاح',
        'delete_error' => 'حدث خطأ أثناء حذف طلب القرض',
        'loading' => 'جاري التحديث...',
    ]
];