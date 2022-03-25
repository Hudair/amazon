<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول السمة:.',
    'active_url' => 'السمة: ليست عنوان URL صالحًا.',
    'after' => 'يجب أن تكون السمة تاريخًا بعد: التاريخ.',
    'after_or_equal' => 'يجب أن تكون السمة تاريخًا بعد: التاريخ أو مساويًا له.',
    'alpha' => 'يجب أن تحتوي السمة على أحرف فقط.',
    'alpha_dash' => 'يجب أن تحتوي السمة: على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن تحتوي السمة: على أحرف وأرقام فقط.',
    'array' => 'يجب أن تكون السمة مصفوفة.',
    'before' => 'يجب أن تكون السمة تاريخًا قبل: التاريخ.',
    'before_or_equal' => 'يجب أن تكون السمة تاريخًا يسبق: التاريخ أو مساويًا له.',
    'between' => [
        'numeric' => 'يجب أن تكون السمة: بين: اقل او: اقصى.',
        'file' => 'يجب أن تكون السمة: بين: اقل و: اقصى كيلوبايت.',
        'string' => 'يجب أن تكون السمة: بين: اقل و: اقصى حرفًا.',
        'array' => 'يجب أن تحتوي السمة: على ما بين: اقل و: اقصى من العناصر.',
    ],
    'boolean' => 'يجب أن يكون حقل السمة صحيحًا أو خطأً.',
    'confirmed' => 'تأكيد السمة غير مطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'السمة: ليست تاريخًا صالحًا.',
    'date_equals' => 'يجب أن تكون السمة تاريخًا مساويًا لـ: التاريخ.',
    'date_format' => 'السمة: لا تطابق التنسيق: format.',
    'different' => 'يجب أن يكون: السمة و: الآخر مختلفين.',
    'digits' => 'يجب أن تكون السمة: أرقامًا وأرقامًا.',
    'digits_between' => 'يجب أن تكون السمة: بين: min و: max أرقام.',
    'dimensions' => 'السمة لها أبعاد صورة غير صالحة.',
    'distinct' => 'يحتوي حقل السمة على قيمة مكررة.',
    'email' => 'يجب أن تكون السمة عنوان بريد إلكتروني صالحًا.',
    'ends_with' => 'يجب أن تنتهي السمة: بأحد القيم التالية:.',
    'exists' => 'السمة المحددة: غير صالحة.',
    'file' => 'يجب أن تكون السمة: ملفًا.',
    'filled' => 'يجب أن يحتوي حقل السمة على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن تكون السمة: أكبر من: القيمة.',
        'file' => 'يجب أن تكون السمة: أكبر من: القيمة كيلوبايت.',
        'string' => 'يجب أن تكون السمة: أكبر من: أحرف القيمة.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'يجب أن تكون السمة: أكبر من أو تساوي: القيمة.',
        'file' => 'يجب أن تكون السمة: أكبر من أو تساوي: القيمة كيلوبايت.',
        'string' => 'يجب أن تكون السمة: أكبر من أو تساوي: أحرف القيمة.',
        'array' => 'يجب أن تحتوي السمة: على عناصر قيمة أو أكثر.',
    ],
    'image' => 'يجب أن تكون السمة صورة.',
    'in' => 'السمة المحددة: غير صالحة.',
    'in_array' => 'حقل السمة: غير موجود في: أخرى.',
    'integer' => 'يجب أن تكون السمة عددًا صحيحًا.',
    'ip' => 'يجب أن تكون السمة: عنوان IP صالحًا.',
    'ipv4' => 'يجب أن تكون السمة: عنوان IPv4 صالحًا.',
    'ipv6' => 'يجب أن تكون السمة عنوان IPv6 صالحًا.',
    'json' => 'يجب أن تكون السمة: سلسلة JSON صالحة.',
    'lt' => [
        'numeric' => 'يجب أن تكون السمة: أقل من: القيمة.',
        'file' => 'يجب أن تكون السمة: أقل من: value كيلوبايت.',
        'string' => 'يجب أن تكون السمة: أقل من: أحرف القيمة.',
        'array' => 'يجب أن تحتوي السمة على أقل من: عناصر القيمة.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'يجب ألا تكون السمة: أكبر من: max.',
        'file' => 'يجب ألا تكون السمة: أكبر من: بحد أقصى كيلوبايت.',
        'string' => 'يجب ألا تكون السمة: أكبر من: الحد الأقصى من الأحرف.',
        'array' => 'يجب ألا تحتوي السمة: على أكثر من: max items.',
    ],
    'mimes' => 'يجب أن تكون السمة: ملفًا من النوع: القيم.',
    'mimetypes' => 'يجب أن تكون السمة: ملفًا من النوع: القيم.',
    'min' => [
        'numeric' => 'يجب أن تكون سمة: على الأقل: min.',
        'file' => 'يجب ألا تقل السمة: عن: دقيقة كيلوبايت.',
        'string' => 'يجب ألا تقل السمة: عن: min حرفًا.',
        'array' => 'يجب أن تحتوي السمة: على الأقل على: min من العناصر.',
    ],
    'multiple_of' => 'يجب أن تكون السمة من مضاعفات: القيمة.',
    'not_in' => 'السمة المحددة: غير صالحة.',
    'not_regex' => 'تنسيق السمة: غير صالح.',
    'numeric' => 'يجب أن تكون السمة رقمًا.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'يجب أن يكون حقل السمة موجودًا.',
    'regex' => 'تنسيق السمة: غير صالح.',
    'required' => ': حقل السمة مطلوب.',
    'required_if' => 'يكون حقل السمة مطلوبًا عندما: الآخر هو: القيمة.',
    'required_unless' => 'حقل السمة مطلوب إلا إذا كان الآخر في: قيم.',
    'required_with' => 'يكون حقل السمة مطلوبًا عندما تكون: القيم موجودة.',
    'required_with_all' => 'يكون حقل السمة مطلوبًا عندما تكون: القيم موجودة.',
    'required_without' => 'يكون حقل السمة مطلوبًا عندما: القيم غير موجودة.',
    'required_without_all' => 'يكون حقل السمة مطلوبًا في حالة عدم وجود أي من: القيم.',
    'prohibited' => 'حقل السمة: محظور.',
    'prohibited_if' => 'يُحظر حقل السمة: عندما: الآخر هو: القيمة.',
    'prohibited_unless' => 'يُحظر حقل السمة: إلا إذا كان الآخر في: القيم.',
    'same' => 'يجب أن يتطابق السمة: و: الآخر.',
    'size' => [
        'numeric' => 'يجب أن تكون السمة: الحجم.',
        'file' => 'يجب أن تكون السمة: الحجم كيلوبايت.',
        'string' => 'يجب أن تكون السمة: أحرف الحجم.',
        'array' => 'يجب أن تحتوي السمة: على عناصر الحجم.',
    ],
    'starts_with' => 'يجب أن تبدأ السمة: بأحد القيم التالية:',
    'string' => 'يجب أن تكون السمة: سلسلة.',
    'timezone' => 'يجب أن تكون السمة: منطقة صالحة.',
    'unique' => 'تم استخدام السمة: بالفعل.',
    'uploaded' => 'فشل تحميل السمة:.',
    'url' => 'تنسيق السمة: غير صالح.',
    'uuid' => 'يجب أن تكون السمة: UUID صالحًا.',

    // Custom app validations
    // 'full_name_required'            => 'اسمك مطلوب',
    'composite_unique'              => 'السمة: القيمة موجودة بالفعل.',
    'register_email_unique'         => 'عنوان البريد الإلكتروني هذا لديه حساب بالفعل. الرجاء محاولة شيء آخر.',
    'role_type_required'            => 'حدد نوع الدور.',
    'attribute_id_required'         => 'حدد السمة.',
    'attribute_type_id_required'    => 'حدد نوع السمة.',
    'attribute_code_required'       => 'مطلوب حقل رمز السمة.',
    'attribute_value_required'      => 'حقل قيمة السمة مطلوب.',
    'category_list_required'        => 'حدد فئة واحدة على الأقل.',
    'manufacturer_required'         => 'حقل الشركة المصنعة مطلوب.',
    'origin_required'               => 'حقل الأصل مطلوب.',
    'offer_start_required'          => 'عندما يكون لديك سعر عرض ، يكون تاريخ بدء العرض مطلوبًا.',
    'offer_start_after'             => ' لا يجوز أن يكون وقت بدء العرض الترويجي وقتًا في الماضي.',
    'offer_end_required'            => 'عندما يكون لديك سعر عرض ، يكون تاريخ انتهاء العرض مطلوبًا.',
    'offer_end_after'               => ' يجب أن يكون وقت انتهاء العرض وقتًا بعد وقت بدء العرض.',
    'variants_required'             => 'المتغيرات المطلوبة',
    'sku-unique'                    => 'تم أخذ قيمة sku: القيمة بالفعل. جرب واحدة جديدة.',
    'sku-distinct'                  => 'المتغير: السمة لها قيمة SKU مكررة.',
    'offer_price-numeric'           => ' ليست قيمة سعر صالحة. يجب أن يكون سعر العرض رقمًا.',
    'email_template_id_required'    => 'قالب البريد الإلكتروني مطلوب.',
    // 'merchant_have_shop'            => 'هذا التاجر لديه متجر.',
    'brand_logo_max'                => 'لا يجوز أن يكون شعار العلامة التجارية أكبر من: بحد أقصى كيلوبايت.',
    'brand_logo_mimes'              => 'يجب أن يكون شعار العلامة التجارية ملفًا من النوع: القيم.',
    'uploaded'                      => 'تجاوز حجم الملف الحد الأقصى للتحميل على الخادم الخاص بك. يرجى التحقق من ملف php.ini.',
    'avatar_required'               => 'Choose an avatar.',
    'subject_required_without'      => 'الموضوع مطلوب إذا كنت لا تستخدم قالبًا.',
    'message_required_without'      => 'الرسالة مطلوبة إذا كنت لا تستخدم قالبًا.',
    'template_id_required_without_all' => 'حدد قالبًا أو منشئ رسالة جديدة.',
    'customer_required'             => 'حدد الزبون.',
    'reply_required_without' => 'حقل الرد مطلوب.',
    'template_id_required_without' => 'حدد قالبًا مطلوبًا عند إعادة التحميل بالقالب.',
    'shipping_zone_tax_id_required' => 'حدد ملف تعريف الضرائب للمنطقة',
    'shipping_zone_country_ids_required' => 'حدد دولة واحدة على الأقل',
    'rest_of_the_world_composite_unique' => 'باقي منطقة الشحن العالمية موجودة بالفعل.',
    'something_went_wrong' => 'شيء ما ليس صحيحا. يرجى مراجعة وحاول مرة أخرى.',
    'shipping_rate_required_unless' => 'حدد سعر الشحن أو حدد الخيار \ "شحن مجاني \"',
    'shipping_range_minimum_min' => 'لا يمكن أن يكون الحد الأدنى للمدى قيمة سالبة',
    'shipping_range_maximum_min' => 'لا يمكن أن يكون النطاق الأقصى أقل من الحد الأدنى للقيمة',
    'csv_mimes'                => 'يجب أن تكون السمة: ملفًا من النوع csv.',
    'import_data_required' => 'مجموعة البيانات غير صالحة للاستيراد. يرجى التحقق من البيانات الخاصة بك وحاول مرة أخرى.',
    'do_action_required'    => 'لم تقدم المدخلات.',
    'do_action_invalid'    => 'الكلمة الأساسية / الإدخال المحدد غير صالح.',
    'recaptcha' => 'يرجى التأكد من أنك إنسان!',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'رسالة مخصصة',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    'upload_rows' => 'يمكنك تحميل كحد أقصى: سجلات الصفوف لكل دفعة.',
    'csv_upload_invalid_data' => 'تحتوي بعض الصفوف على بيانات غير صالحة لا يمكن معالجتها. يرجى التحقق من البيانات الخاصة بك وحاول مرة أخرى.',
    'slider_image_required' => 'مطلوب صورة شريط التمرير',
    'banner_image_required' => 'صورة الشعار مطلوبة',
    'select_the_item' => 'حدد العنصر',
    'banner_group_id_required' => 'الرجاء تحديد مجموعة لافتة',
    "valid_css" => "قد تحتوي السمة: على CSS صالح فقط."
];
