<?php

return [
    'create' => [
        'title' => 'إنشاء وردية - :company',
        'page_title' => 'إنشاء وردية',
        'back_to_shifts' => 'العودة إلى الورديات',
        'description' => 'إعداد جدول وردية جديد لفرق البناء',

        'shift_information' => 'معلومات الوردية',
        'shift_name' => 'اسم الوردية',
        'placeholder_shift_name' => 'مثال: وردية البناء الصباحية',
        'shift_type' => 'نوع الوردية',
        'select_shift_type' => 'اختر نوع الوردية',
        'type_morning' => 'وردية الصباح',
        'type_evening' => 'وردية المساء',
        'type_night' => 'وردية الليل',
        'type_flexible' => 'وردية مرنة',

        'shift_timing' => 'توقيت الوردية',
        'start_time' => 'وقت البدء',
        'end_time' => 'وقت الانتهاء',
        'break_duration' => 'مدة الراحة',
        'placeholder_break' => 'مثال: 60',
        'total_working_hours' => 'إجمالي ساعات العمل',

        'location_assignment' => 'الموقع والتخصيص',
        'work_location' => 'موقع العمل',
        'select_location' => 'اختر الموقع',
        'max_employees' => 'أقصى عدد من الموظفين',
        'placeholder_max_employees' => 'مثال: 25',
        'supervisor' => 'المشرف',
        'select_supervisor' => 'اختر المشرف',
        'associated_project' => 'المشروع المرتبط',
        'select_project_optional' => 'اختر المشروع (اختياري)',

        'shift_configuration' => 'إعدادات الوردية',
        'working_days' => 'أيام العمل',
        'overtime_settings' => 'إعدادات العمل الإضافي',
        'overtime_allowed' => 'السماح بالعمل الإضافي',
        'no_overtime' => 'لا عمل إضافي',
        'shift_description' => 'وصف الوردية',

        'preview' => 'معاينة الوردية',
        'preview_new_shift' => 'وردية جديدة',
        'preview_type_default' => 'صباحي',
        'preview_time_default' => 'حدد وقت البدء والانتهاء',
        'preview_location_none' => 'لم يتم اختيار موقع',
        'preview_capacity_none' => 'لا حد محدد',

        'create_shift' => 'إنشاء الوردية',
        'cancel' => 'إلغاء',
    ],
    'edit' => [
        'title' => 'تعديل الوردية - :company',
        'page_title' => 'تعديل الوردية',
        'back_to_shifts' => 'العودة إلى الورديات',
        'view_details' => 'عرض التفاصيل',
        'update_note' => 'تحديث إعدادات وردية :name',

        'update_shift_information' => 'تحديث معلومات الوردية',
        'update_shift_timing' => 'تحديث توقيت الوردية',
        'location_assignment_updates' => 'تحديثات الموقع والتخصيص',

        'current_employee_assignments' => 'تخصيصات الموظفين الحالية',
        'manage_assignments' => 'إدارة التخصيصات',
        'add_employees' => 'إضافة موظفين',

        'audit_recent_changes' => 'التغييرات الأخيرة',

        'update_shift' => 'تحديث الوردية',
        'cancel_changes' => 'إلغاء التغييرات',

        'delete_shift_confirm' => 'هل أنت متأكد من حذف هذه الوردية؟ هذا الإجراء لا يمكن التراجع عنه وسيزيل جميع تخصيصات الموظفين.',
        'delete_success' => 'تم حذف الوردية بنجاح',
        'delete_error' => 'خطأ في حذف الوردية',
    ],
     'index' => [
        'title' => 'إدارة الورديات - :company',
        'page_title' => 'إدارة الورديات',
        'description' => 'إدارة جميع جداول الورديات وتخصيصات الموظفين وتخطيط السعة عبر مواقع البناء',
        'create_shift' => 'إنشاء وردية',

        'stats_morning' => 'عمال وردية الصباح',
        'stats_evening' => 'عمال وردية المساء',
        'stats_night' => 'عمال وردية الليل',
        'stats_total' => 'إجمالي الورديات النشطة',
        'stats_total_note' => 'جميع الورديات مجتمعة',

        'current_schedule' => 'جدول الورديات الحالي',

        'table' => [
            'shift_name' => 'اسم الوردية',
            'type' => 'النوع',
            'time' => 'الوقت',
            'location' => 'الموقع',
            'employees' => 'الموظفين',
            'status' => 'الحالة',
            'actions' => 'الإجراءات',
        ],

        'multiple_sites' => 'مواقع متعددة',
        'workers' => 'عمال',
        'status_active' => 'نشط',
        'no_shifts' => 'لم يتم تكوين أي ورديات بعد',

        'weekly_schedule' => 'جدول الورديات الأسبوعي',

        'days' => [
            'mon' => 'الإثنين',
            'tue' => 'الثلاثاء',
            'wed' => 'الأربعاء',
            'thu' => 'الخميس',
            'fri' => 'الجمعة',
            'sat' => 'السبت',
            'sun' => 'الأحد',
        ],

        'assign_alert' => 'سيتم تنفيذ وظيفة تخصيص الموظفين',
    ],
    'show' => [
        'title' => 'تفاصيل الوردية - :company',
        'page_title' => 'تفاصيل الوردية',
        'back_to_shifts' => 'العودة إلى الورديات',
        'default_name' => 'وردية الصباح',
        'default_location' => 'موقع أ - غورغاو',

        'labels' => [
            'currently_assigned' => 'معين حالياً',
            'working_hours' => 'ساعات',
            'shift_name' => 'اسم الوردية',
            'type' => 'النوع',
            'start_time' => 'وقت البدء',
            'end_time' => 'وقت الانتهاء',
            'break_duration' => 'مدة الراحة',
            'minutes' => 'دقائق',
            'hours' => 'ساعات',
            'work_location' => 'موقع العمل',
            'supervisor' => 'المشرف',
            'project' => 'المشروع',
            'max_capacity' => 'السعة القصوى',
            'employees' => 'موظفين',
            'overtime_allowed' => 'السماح بالعمل الإضافي',
        ],

        'section' => [
            'shift_information' => 'وردية',
            'location_assignment' => 'الموقع والتخصيص',
            'working_days_schedule' => 'جدول أيام العمل',
            'assigned_employees' => 'الموظفين المعينين',
            'shift_description' => 'وصف الوردية',
        ],

        'capacity' => 'السعة',
        'yes' => 'نعم',
        'no' => 'لا',

        'days' => [
            'working' => 'عمل',
            'off' => 'إجازة',
        ],

        'assign_more' => 'تعيين المزيد',
        'view_more_count' => 'عرض +:count آخرين',
        'view_all_hint' => 'عرض جميع الموظفين',

        'duplicate' => 'تكرار الوردية',
        'activate' => 'تفعيل الوردية',
        'delete' => 'حذف الوردية',

        'assign_alert' => 'سيتم تنفيذ وظيفة تعيين الموظفين',
        'remove_employee_confirm' => 'هل أنت متأكد من إزالة هذا الموظف من الوردية؟',
        'remove_employee_alert' => 'تم إزالة الموظف من الوردية',
        'view_all' => 'سيتم تنفيذ وظيفة عرض جميع الموظفين',
        'duplicate_confirm' => 'هل تريد إنشاء نسخة مكررة من هذه الوردية بنفس الإعدادات؟',
        'duplicate_alert' => 'تم تكرار الوردية بنجاح',
        'activate_alert' => 'سيتم تنفيذ وظيفة تفعيل الوردية',
        'delete_confirm' => 'هل أنت متأكد من حذف هذه الوردية؟ سيتم إزالة جميع التخصيصات.',
        'delete_success' => 'تم حذف الوردية بنجاح',
        'delete_error' => 'خطأ في حذف الوردية',
    ],

];
