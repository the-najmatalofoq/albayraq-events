<?php

return [
    'countries' => [
        ['code' => 'SA', 'phone' => '+966', 'en' => 'Saudi Arabia', 'ar' => 'المملكة العربية السعودية', 'nat_en' => 'Saudi', 'nat_ar' => 'سعودي'],
        ['code' => 'AE', 'phone' => '+971', 'en' => 'United Arab Emirates', 'ar' => 'الإمارات العربية المتحدة', 'nat_en' => 'Emirati', 'nat_ar' => 'إماراتي'],
        ['code' => 'BH', 'phone' => '+973', 'en' => 'Bahrain', 'ar' => 'البحرين', 'nat_en' => 'Bahraini', 'nat_ar' => 'بحريني'],
        ['code' => 'KW', 'phone' => '+965', 'en' => 'Kuwait', 'ar' => 'الكويت', 'nat_en' => 'Kuwaiti', 'nat_ar' => 'كويتي'],
        ['code' => 'OM', 'phone' => '+968', 'en' => 'Oman', 'ar' => 'عمان', 'nat_en' => 'Omani', 'nat_ar' => 'عماني'],
        ['code' => 'QA', 'phone' => '+974', 'en' => 'Qatar', 'ar' => 'قطر', 'nat_en' => 'Qatari', 'nat_ar' => 'قطري'],
        ['code' => 'YE', 'phone' => '+967', 'en' => 'Yemen', 'ar' => 'اليمن', 'nat_en' => 'Yemeni', 'nat_ar' => 'يمني'],
    ],
    'states' => [
        'SA' => [
            ['en' => 'Riyadh Province', 'ar' => 'منطقة الرياض', 'cities' => [
                ['en' => 'Riyadh', 'ar' => 'الرياض'],
                ['en' => 'Al Kharj', 'ar' => 'الخرج'],
            ]],
            ['en' => 'Makkah Province', 'ar' => 'منطقة مكة المكرمة', 'cities' => [
                ['en' => 'Makkah', 'ar' => 'مكة المكرمة'],
                ['en' => 'Jeddah', 'ar' => 'جدة'],
                ['en' => 'Taif', 'ar' => 'الطائف'],
            ]],
            ['en' => 'Eastern Province', 'ar' => 'المنطقة الشرقية', 'cities' => [
                ['en' => 'Dammam', 'ar' => 'الدمام'],
                ['en' => 'Khobar', 'ar' => 'الخبر'],
                ['en' => 'Dhahran', 'ar' => 'الظهران'],
            ]],
            ['en' => 'Madinah Province', 'ar' => 'منطقة المدينة المنورة', 'cities' => [
                ['en' => 'Madinah', 'ar' => 'المدينة المنورة'],
                ['en' => 'Yanbu', 'ar' => 'ينبع'],
            ]],
            ['en' => 'Asir Province', 'ar' => 'منطقة عسير', 'cities' => [
                ['en' => 'Abha', 'ar' => 'أبها'],
                ['en' => 'Khamis Mushait', 'ar' => 'خميس مشيط'],
            ]],
        ],
        'AE' => [
            ['en' => 'Dubai', 'ar' => 'دبي', 'cities' => [
                ['en' => 'Dubai City', 'ar' => 'مدينة دبي'],
            ]],
        ],
    ],
];
