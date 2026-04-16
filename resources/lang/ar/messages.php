<?php
// resources/lang/ar/messages.php
return [
    'success' => 'نجاح',
    'created' => 'تم الإنشاء بنجاح',
    'updated' => 'تم التحديث بنجاح',
    'not_found' => 'غير موجود',
    'forbidden' => 'ممنوع',
    'unauthorized' => 'غير مصرح',
    'validation_failed' => 'فشل التحقق',
    'error' => 'حدث خطأ ما',
    'auth' => [
        'registered' => 'تم التسجيل بنجاح',
        'logged_in' => 'تم تسجيل الدخول بنجاح',
        'logged_out' => 'تم تسجيل الخروج بنجاح',
        'token_refreshed' => 'تم تجديد التوكين بنجاح',
        'invalid_credentials' => 'بيانات الدخول غير صحيحة',
        'token_mismatch' => 'انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى.',
    ],
    'user' => [
        'account_not_active' => 'حسابك غير مفعل.',
        'user_already_exists' => 'يوجد حساب بالفعل بهذه المعلومات.'
    ],

    'errors' => [
        'validation_failed' => 'البيانات المقدمة غير صالحة.',
        'unauthenticated' => 'أنت غير مصدق.',
        'forbidden' => 'ليس لديك إذن للقيام بهذا الإجراء.',
        'not_found' => 'الأصل المطلوب غير موجود.',
        'token_mismatch' => 'انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى.',
        'too_many_requests' => 'طلبات كثيرة جداً، يرجى المحاولة مرة أخرى لاحقاً.',
        'server_error' => 'حدث خطأ داخلي في الخادم.',
        'service_unavailable' => 'الخدمة غير متوفرة مؤقتاً.',
        'user_already_exists' => 'المستخدم بهذا البريد مسجل مسبقاً.',
        'invalid_credentials' => 'البريد أو كلمة المرور غير صحيحة.',
        'email_not_verified' => 'البريد الإلكتروني غير مفعل. يرجى التحقق من بريدك الإلكتروني للحصول على رمز التحقق.',
        'email_already_exists' => 'البريد الإلكتروني مسجل مسبقاً.',
        'user_not_approved' => 'طلب الانضمام الخاص بك لم تتم الموافقة عليه بعد.',
        'user_pending' => 'طلب الانضمام الخاص بك قيد المراجعة.',
        'bank_already_exists' => 'يوجد حساب بنكي مسجل مسبقاً بهذا الآيبان أو رقم الحساب.',
        'user_not_found' => 'المستخدم المطلوب غير موجود.',
        'phone_already_exists' => 'رقم الهاتف مسجل مسبقاً.',
        'pending_update_request' => 'لديك طلب سابق قيد المراجعة لـ :target.',
    ],
    'targets' => [
        'user_info' => 'بياناتك الشخصية',
        'employee_profile' => 'بيانات الملف الشخصي',
        'medical_record' => 'السجل الطبي',
        'bank_account' => 'الحساب البنكي',
    ],
];
