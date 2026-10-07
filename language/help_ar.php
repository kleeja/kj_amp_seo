<?php
//
// kj_amp_seo, its guide on the help page of the control panel
// Arabic
//
// The guide is built by the admin_help_guides hook in init.php, the texts here can have HTML.
// The keys are the ones of help_en.php, a key that is missing here is shown in English.
//  - names in <strong> are written as the control panel shows them: the words of Kleeja are in lang/ar/,
//    the words of the plugin in kj_amp_seo_translations() of init.php
//  - a <code> that begins or ends with a symbol has dir="ltr", or the symbol moves to the other side
//

return [
    'KJ_AMP_SEO_HELP_TITLE' => 'صفحات AMP لمحركات البحث',
    'KJ_AMP_SEO_HELP_INTRO' =>
        'أنشئ لكل صفحة تحميل نسخة AMP سريعة. تجدها محركات البحث عبر رابط في ترويسة الصفحة العادية، ويحصل زوارك على صفحة خفيفة تُفتح بسرعة على الهواتف، مع أزرار مشاركة وإعلانات AdSense إن أردت.',

    //
    // what the plugin does
    //
    'KJ_AMP_SEO_HELP_FEATURE_1' =>
        'لكل صفحة تحميل نسخة AMP على العنوان نفسه بعد إضافة <code dir="ltr">amp_page=1</code> إليه، مثل <code dir="ltr">do.php?id=5&amp;amp_page=1</code> أو <code dir="ltr">download5.html?amp_page=1</code>.',
    'KJ_AMP_SEO_HELP_FEATURE_2' =>
        'تشير الصفحة العادية إلى نسختها بصيغة AMP عبر <code dir="ltr">&lt;link rel="amphtml"&gt;</code>، وتشير نسخة AMP إلى الصفحة العادية بوصفها الصفحة الأصلية، فتعاملهما محركات البحث صفحةً واحدة.',
    'KJ_AMP_SEO_HELP_FEATURE_3' =>
        'تعرض صفحة AMP اسم الملف ونوعه وحجمه وتاريخ رفعه وعدد تحميلاته ومن رفعه، ورابط الإبلاغ وزر التحميل، مع قائمة بصفحات موقعك.',
    'KJ_AMP_SEO_HELP_FEATURE_4' =>
        'أزرار اختيارية لمشاركة صفحة التحميل على X (تويتر) وفيسبوك وواتساب وبنترست، أو عبر البريد الإلكتروني.',
    'KJ_AMP_SEO_HELP_FEATURE_5' =>
        'ما يصل إلى ثلاثة إعلانات AdSense: إعلان فوق بيانات الملف، وإعلان فوق زر التحميل، وشريط إعلاني ثابت أسفل الشاشة.',

    //
    // how to use it
    //
    'KJ_AMP_SEO_HELP_STEP_TITLE' => 'إعداد صفحات AMP',
    'KJ_AMP_SEO_HELP_STEP_1' => 'افتح <strong>إعدادات</strong>، ثم <strong>خيارات صفحات AMP</strong>.',
    'KJ_AMP_SEO_HELP_STEP_2' =>
        'اجعل <strong>تفعيل صفحات AMP للتحميل</strong> على <strong>نعم</strong>، واختر هل تريد <strong>إظهار أزرار المشاركة</strong>.',
    'KJ_AMP_SEO_HELP_STEP_3' =>
        'لعرض الإعلانات، أدخل <strong>معرّف الناشر في AdSense</strong> ومعرّف كل إعلان تريده. الإعلان الذي تترك معرّفه فارغًا لا يظهر.',
    'KJ_AMP_SEO_HELP_STEP_4' => 'اضغط <strong>تحديث الإعدادات</strong>.',
    'KJ_AMP_SEO_HELP_STEP_5' =>
        'افتح صفحة تحميل أحد الملفات، وأضف <code dir="ltr">amp_page=1</code> إلى عنوانها، ثم تحقق من النتيجة.',

    //
    // common questions
    //
    'KJ_AMP_SEO_HELP_FAQ_Q_1' => 'أين أجد معرّف الناشر ومعرّفات الإعلانات؟',
    'KJ_AMP_SEO_HELP_FAQ_A_1' =>
        'في الكود الذي يعطيك إياه AdSense للوحدة الإعلانية: معرّف الناشر هو قيمة <code dir="ltr">data-ad-client</code> مثل <code dir="ltr">ca-pub-1234567890123456</code>، ومعرّف الإعلان هو قيمة <code dir="ltr">data-ad-slot</code> مثل <code dir="ltr">1234567890</code>. أنشئ وحدة إعلانية لكل موضع.',
    'KJ_AMP_SEO_HELP_FAQ_Q_2' => 'لماذا لا تظهر الإعلانات؟',
    'KJ_AMP_SEO_HELP_FAQ_A_2' =>
        'لا تعرض الإضافة الإعلان إلا إذا كان معرّف الناشر <code dir="ltr">ca-pub-</code> تليه أرقام، وكان معرّف الإعلان أرقامًا فقط، فتأكد من خلوّهما من المسافات والرموز الزائدة. وقد تحتاج الوحدة الإعلانية الجديدة بعض الوقت قبل أن يبدأ AdSense في ملئها.',
    'KJ_AMP_SEO_HELP_FAQ_Q_3' => 'هل تنتظر صفحة AMP قبل إظهار زر التحميل؟',
    'KJ_AMP_SEO_HELP_FAQ_A_3' =>
        'لا. لا تستطيع صفحات AMP تشغيل سكربت العدّ التنازلي الذي في الصفحة العادية، لذا يظهر الزر فورًا مهما كانت قيمة <strong>عدد الثواني قبل بدء التحميل</strong>.',
    'KJ_AMP_SEO_HELP_FAQ_Q_4' => 'كيف أتحقق من سلامة صفحة AMP؟',
    'KJ_AMP_SEO_HELP_FAQ_A_4' =>
        'الصق عنوانها في <a href="https://validator.ampproject.org/" target="_blank" rel="noopener">مدقق AMP</a>، أو افتحها بعد إضافة <code dir="ltr">#development=1</code> إلى آخر عنوانها، واقرأ وحدة التحكم في المتصفح.',
    'KJ_AMP_SEO_HELP_FAQ_Q_5' => 'هل تغيّر الإضافة ستايل موقعي؟',
    'KJ_AMP_SEO_HELP_FAQ_A_5' =>
        'لا. لا تستطيع صفحات AMP تحميل ملفات CSS والسكربتات الخاصة بالستايل، لذا لصفحة AMP تصميم صغير خاص بها داخل الإضافة. ولا يُضاف إلى صفحة التحميل العادية سوى رابط amphtml في ترويستها.',

    //
    // beside the guide
    //
    'KJ_AMP_SEO_HELP_TIP_1' =>
        'استخدم وحدة إعلانية بمقاس 320×50 للإعلان المثبّت، فارتفاع الشريط لا يتجاوز 100 بكسل، ويُقص الإعلان الأطول منه.',
    'KJ_AMP_SEO_HELP_TIP_2' =>
        'تشارك أزرار المشاركة عنوان صفحة التحميل العادية، فيرى من يفتح الرابط المشارك ستايل موقعك.',
    'KJ_AMP_SEO_HELP_TIP_3' =>
        'لم تعد Google تقدّم صفحات AMP على غيرها في نتائج البحث، والفائدة صفحة تُفتح بسرعة على الاتصالات البطيئة.',

    'KJ_AMP_SEO_HELP_WARNING_1' =>
        'عند جعل <strong>تفعيل صفحات AMP للتحميل</strong> على <strong>لا</strong>، يُحذف رابط amphtml وتعرض عناوين AMP صفحة التحميل العادية.',
    'KJ_AMP_SEO_HELP_WARNING_2' =>
        'حذف الإضافة يحذف إعداداتها، ومنها معرّفات AdSense. دوّنها قبل الحذف إن كنت ستحتاج إليها لاحقًا.',

    //
    // in the guide of the settings, whose page has the tab of the plugin
    //
    'KJ_AMP_SEO_HELP_NOTE' =>
        'تبويب <strong>خيارات صفحات AMP</strong> تابع لإضافة <strong>صفحات AMP لمحركات البحث</strong>. <a href="#help-kj_amp_seo">اقرأ دليلها</a>.',
];
