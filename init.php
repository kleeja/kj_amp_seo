<?php
// Kleeja Plugin
// kj_amp_seo
// Version: 1.2.0
// Developer: Kleeja team

// Prevent illegal run
if (!defined('IN_PLUGINS_SYSTEM')) {
    exit();
}

// Plugin Basic Information
$kleeja_plugin['kj_amp_seo']['information'] = [
    // The casual name of this plugin, anything can a human being understands
    'plugin_title' => [
        'en' => 'AMP SEO',
        'ar' => 'صفحات AMP لمحركات البحث',
    ],
    // Who wrote this plugin?
    'plugin_developer' => 'Kleeja.net',
    //settings page, if there is one (what after ? like cp=j_plugins)
    'settings_page' => 'cp=options&smt=kj_amp_seo',
    // This plugin version
    'plugin_version' => '1.2.0',
    // Explain what is this plugin, why should I use it?
    'plugin_description' => [
        'en' =>
            'Give every download page a fast AMP version that search engines can find, with optional share buttons and AdSense ads.',
        'ar' => 'تُنشئ لكل صفحة تحميل نسخة AMP سريعة تجدها محركات البحث، مع أزرار مشاركة وإعلانات AdSense اختيارية.',
    ],
    // Min version of Kleeja that's requiered to run this plugin
    'plugin_kleeja_version_min' => '4.0.0',
    // Max version of Kleeja that support this plugin, use 0 for unlimited
    'plugin_kleeja_version_max' => '4.9',
    // Should this plugin run before others?, 0 is normal, and higher number has high priority
    'plugin_priority' => 0,
];

//after installation message, you can remove it, it's not requiered
$kleeja_plugin['kj_amp_seo']['first_run']['ar'] = "
تمنح هذه الإضافة كل صفحة تحميل نسخة AMP سريعة، تجدها محركات البحث عبر رابط في ترويسة الصفحة العادية،
مع أزرار مشاركة وإعلانات AdSense اختيارية. تجد خياراتها في تبويب «خيارات صفحات AMP» من صفحة الإعدادات.
<br>
<a href='./index.php?cp=options&amp;smt=kj_amp_seo'>خيارات صفحات AMP</a> -
<a href='https://github.com/kleeja/kj_amp_seo/issues' target='_blank' rel='noopener'>الإبلاغ عن مشكلة</a>
";

$kleeja_plugin['kj_amp_seo']['first_run']['en'] = "
This plugin gives every download page a fast AMP version, which search engines find through a link
in the head of the regular page, with optional share buttons and AdSense ads.
Its options are in the AMP settings tab of the Settings page.
<br>
<a href='./index.php?cp=options&amp;smt=kj_amp_seo'>AMP settings</a> -
<a href='https://github.com/kleeja/kj_amp_seo/issues' target='_blank' rel='noopener'>Report a problem</a>
";

// Plugin Installation function
$kleeja_plugin['kj_amp_seo']['install'] = function ($plg_id) {
    $options = [];

    foreach (kj_amp_seo_configs() as $name => $setting) {
        $options[$name] = [
            'value' => $setting['value'],
            'html' => $setting['html'],
            'order' => $setting['order'],
            'plg_id' => $plg_id,
            'type' => 'kj_amp_seo',
        ];
    }

    add_config_r($options);

    foreach (kj_amp_seo_translations() as $lang_id => $words) {
        add_olang($words, $lang_id, $plg_id);
    }
};

// the words and the fields of the settings are written again, so a new version brings its own (1.2.0 rewrote all of them),
// with queries of its own: add_olang(), delete_olang(), update_config() and delete_cache() run hooks,
// or need the groups, and the plugins are still loading.
// $kleeja_plugin isn't a global, Kleeja includes this file in a method, so the closure takes the information above with it
$kleeja_plugin['kj_amp_seo']['update'] = function ($old_version, $new_version) use ($kleeja_plugin) {
    global $SQL, $dbprefix;

    $result = $SQL->build([
        'SELECT' => 'plg_id',
        'FROM' => "{$dbprefix}plugins",
        'WHERE' => 'plg_name = :name',
        'BIND' => ['name' => 'kj_amp_seo'],
    ]);

    $row = $SQL->fetch_array($result);
    $plg_id = (int) ($row['plg_id'] ?? 0);
    $SQL->freeresult($result);

    //a plg_id of 0 would delete the words of the core and of every plugin
    if ($plg_id > 0) {
        $SQL->build([
            'DELETE' => "{$dbprefix}lang",
            'WHERE' => 'plg_id = :plg_id',
            'BIND' => ['plg_id' => $plg_id],
        ]);

        foreach (kj_amp_seo_translations() as $lang_id => $words) {
            foreach ($words as $word => $trans) {
                $SQL->build([
                    'INSERT' => 'word, trans, lang_id, plg_id',
                    'INTO' => "{$dbprefix}lang",
                    'VALUES' => ':word, :trans, :lang_id, :plg_id',
                    'BIND' => ['word' => $word, 'trans' => $trans, 'lang_id' => $lang_id, 'plg_id' => $plg_id],
                ]);
            }
        }
    }

    // the fields of the settings page, and their order, the values are kept
    foreach (kj_amp_seo_configs() as $name => $setting) {
        $SQL->build([
            'UPDATE' => "{$dbprefix}config",
            'SET' => '`option` = :option, display_order = :display_order',
            'WHERE' => 'name = :name',
            'BIND' => ['option' => $setting['html'], 'display_order' => $setting['order'], 'name' => $name],
        ]);
    }

    // the developer and the description that versions before 1.2.0 were installed with,
    // the list of plugins shows them while the plugin is disabled
    if (version_compare($old_version, '1.2.0', '<')) {
        $SQL->build([
            'UPDATE' => "{$dbprefix}plugins",
            'SET' => 'plg_author = :author, plg_dsc = :description',
            'WHERE' => 'plg_name = :name',
            'BIND' => [
                'author' => 'Kleeja.net',
                'description' => kleeja_html_encode(
                    $kleeja_plugin['kj_amp_seo']['information']['plugin_description']['en'],
                ),
                'name' => 'kj_amp_seo',
            ],
        ]);
    }

    // the cached words and settings, and the compiled AMP page, which is new in 1.2.0;
    // an update doesn't delete the compiled templates, the page is compiled again when it is opened
    $cached = array_merge(glob(PATH . 'cache/data_lang*.php') ?: [], glob(PATH . 'cache/data_config.php') ?: [], [
        PATH . 'cache/tpl_amp' . md5(__DIR__) . '.php',
    ]);

    foreach ($cached as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }
};

// Plugin Uninstallation, function to be called at unistalling
$kleeja_plugin['kj_amp_seo']['uninstall'] = function ($plg_id) {
    delete_config(array_keys(kj_amp_seo_configs()));

    //a plg_id of 0 would delete the words of the core and of every plugin
    if ((int) $plg_id > 0) {
        foreach (array_keys(kj_amp_seo_translations()) as $lang_id) {
            delete_olang(null, $lang_id, (int) $plg_id);
        }
    }
};

// Plugin functions
$kleeja_plugin['kj_amp_seo']['functions'] = [
    //the words of the plugin on the settings page, in English for a language that has no translation of them
    'begin_admin_page' => function ($args) {
        $olang = (array) ($args['olang'] ?? []) + kj_amp_seo_translations()['en'];

        return compact('olang');
    },

    //the download page of a file, before Kleeja shows it
    'b4_showsty_downlaod_id_filename' => function ($args) {
        global $config;

        if (empty($config['kj_amp_seo_enable']) || empty($args['show_style'])) {
            return;
        }

        $canonical = kj_amp_seo_canonical_url($args['file_info']);

        // the regular page links to its AMP version, in the head that Saaheader() prints next
        if (!ig('amp_page')) {
            define('KJ_AMP_SEO_AMPHTML', $canonical . (str_contains($canonical, '?') ? '&amp;' : '?') . 'amp_page=1');

            return;
        }

        kj_amp_seo_show_page($canonical);

        return ['show_style' => false];
    },

    //the amphtml link of the download page
    'Saaheader_links_func' => function ($args) {
        if (!defined('KJ_AMP_SEO_AMPHTML')) {
            return;
        }

        $extra = $args['extra'] . '<link rel="amphtml" href="' . KJ_AMP_SEO_AMPHTML . '">';

        return compact('extra');
    },

    // the guide of the plugin on the help page of the control panel, its words are in language/help_{code}.php
    'admin_help_guides' => function ($args) {
        $help_guides = $args['help_guides'];
        $words = kj_amp_seo_help_words();

        // the name of the plugin is the key, so Kleeja knows that the plugin has its guide, and shows its icon.
        // The options of the plugin are a tab of the settings page, whose help button opens the guide of Kleeja,
        // so it has no 'page', and the guide of the settings links to it
        $help_guides['kj_amp_seo'] = [
            'group' => 'plugins',
            'title' => $words['KJ_AMP_SEO_HELP_TITLE'],
            'intro' => $words['KJ_AMP_SEO_HELP_INTRO'],
            'link' => './?cp=options&amp;smt=kj_amp_seo',
            // tips and warnings are shown beside the others on wide screens
            'sections' => [
                kj_amp_seo_help_section($words, 'features', 'FEATURE'),
                kj_amp_seo_help_section($words, 'steps', 'STEP'),
                kj_amp_seo_help_section($words, 'faq', 'FAQ'),
                kj_amp_seo_help_section($words, 'tips', 'TIP'),
                kj_amp_seo_help_section($words, 'warnings', 'WARNING'),
            ],
        ];

        if (isset($help_guides['settings'])) {
            $help_guides['settings']['sections'][] = ['type' => 'text', 'text' => $words['KJ_AMP_SEO_HELP_NOTE']];
        }

        return compact('help_guides');
    },
];

/**
 * special functions
 */

if (!function_exists('kj_amp_seo_configs')) {
    /**
     * the settings of the plugin, with the values that it is installed with, their order,
     * and their fields on the settings page, which adds the classes of Bootstrap to them
     * @return array
     */
    function kj_amp_seo_configs()
    {
        $yes_no = function ($name) {
            $html = '';

            foreach (['1' => 'YES', '0' => 'NO'] as $value => $word) {
                $html .=
                    '<label>{lang.' .
                    $word .
                    '}<input type="radio" id="' .
                    $name .
                    ($value ? '' : '_no') .
                    '" name="' .
                    $name .
                    '" value="' .
                    $value .
                    '"<IF NAME="con.' .
                    $name .
                    '==' .
                    $value .
                    '"> checked="checked"</IF> /></label>';
            }

            return $html;
        };

        // the IDs of AdSense are written from left to right, the hint of each field has an example
        $text = function ($name) {
            return '<input type="text" id="' .
                $name .
                '" name="' .
                $name .
                '" value="{con.' .
                $name .
                '}" dir="ltr" autocomplete="off" spellcheck="false" />';
        };

        return [
            'kj_amp_seo_enable' => ['value' => '1', 'order' => 1, 'html' => $yes_no('kj_amp_seo_enable')],
            'kj_amp_seo_share_buttons' => [
                'value' => '1',
                'order' => 2,
                'html' => $yes_no('kj_amp_seo_share_buttons'),
            ],
            'kj_amp_seo_adsense_client' => [
                'value' => '',
                'order' => 3,
                'html' => $text('kj_amp_seo_adsense_client'),
            ],
            'kj_amp_seo_top_ad' => ['value' => '', 'order' => 4, 'html' => $text('kj_amp_seo_top_ad')],
            'kj_amp_seo_middle_ad' => [
                'value' => '',
                'order' => 5,
                'html' => $text('kj_amp_seo_middle_ad'),
            ],
            'kj_amp_seo_sticky_ad' => [
                'value' => '',
                'order' => 6,
                'html' => $text('kj_amp_seo_sticky_ad'),
            ],
        ];
    }

    /**
     * the address of the regular download page of a file, in the form it was opened with,
     * it is the canonical page of the AMP version
     * @param  array  $file_info
     * @return string encoded for HTML
     */
    function kj_amp_seo_canonical_url($file_info)
    {
        global $config;

        if (ig('filename')) {
            $name = rawurlencode(htmlspecialchars_decode((string) $file_info['name'], ENT_QUOTES));

            return $config['siteurl'] .
                ($config['mod_writer']
                    ? 'downloadf-' . str_replace('.', '-', $name) . '.html'
                    : 'do.php?filename=' . $name);
        }

        return $config['siteurl'] .
            ($config['mod_writer']
                ? 'download' . (int) $file_info['id'] . '.html'
                : 'do.php?id=' . (int) $file_info['id']);
    }

    /**
     * the AdSense ads of the AMP page, an ad is shown when the publisher ID and its slot ID are valid
     * @return array the publisher ID and the slot of each place, or false
     */
    function kj_amp_seo_ads()
    {
        global $config;

        $value = function ($name) use ($config) {
            return trim(htmlspecialchars_decode((string) ($config[$name] ?? ''), ENT_QUOTES));
        };

        // the publisher ID is ca-pub- and digits, the ID that the AdSense account shows has no ca-
        $client = $value('kj_amp_seo_adsense_client');
        $client = preg_match('/^pub-\d+$/', $client) ? 'ca-' . $client : $client;
        $client = preg_match('/^ca-pub-\d+$/', $client) ? $client : false;

        $ads = ['client' => $client];

        foreach (['top', 'middle', 'sticky'] as $place) {
            $slot = $value('kj_amp_seo_' . $place . '_ad');
            $ads[$place] = $client && preg_match('/^\d+$/', $slot) ? $slot : false;
        }

        return $ads;
    }

    /**
     * the menus of the AMP page, the links of the header of Kleeja
     * @return array
     */
    function kj_amp_seo_menu()
    {
        global $config, $lang, $usrcp;

        $user_is = (bool) $usrcp->name();
        $link = function ($rewrite, $plain) use ($config) {
            return $config['siteurl'] . ($config['mod_writer'] ? $rewrite : $plain);
        };

        $menu = [
            ['title' => $lang['INDEX'], 'url' => $config['siteurl'], 'show' => true],
            ['title' => $lang['RULES'], 'url' => $link('rules.html', 'go.php?go=rules'), 'show' => true],
            ['title' => $lang['GUIDE'], 'url' => $link('guide.html', 'go.php?go=guide'), 'show' => true],
            [
                'title' => $lang['STATS'],
                'url' => $link('stats.html', 'go.php?go=stats'),
                'show' => $config['allow_stat_pg'] && user_can('access_stats'),
            ],
            [
                'title' => $lang['REPORT'],
                'url' => $link('report.html', 'go.php?go=report'),
                'show' => user_can('access_report'),
            ],
            [
                'title' => $lang['CALL'],
                'url' => $link('call.html', 'go.php?go=call'),
                'show' => user_can('access_call'),
            ],
            ['title' => $lang['PROFILE'], 'url' => $link('profile.html', 'ucp.php?go=profile'), 'show' => $user_is],
            [
                'title' => $lang['YOUR_FILEUSER'],
                'url' => $link('fileuser.html', 'ucp.php?go=fileuser'),
                'show' => $config['enable_userfile'] && user_can('access_fileuser'),
            ],
            $user_is
                ? ['title' => $lang['LOGOUT'], 'url' => $link('logout.html', 'ucp.php?go=logout'), 'show' => true]
                : ['title' => $lang['LOGIN'], 'url' => $link('login.html', 'ucp.php?go=login'), 'show' => true],
            [
                'title' => $lang['REGISTER'],
                'url' => $link('register.html', 'ucp.php?go=register'),
                'show' => !$user_is && $config['register'],
            ],
        ];

        return array_values(array_filter($menu, fn($item) => $item['show']));
    }

    /**
     * print the AMP version of the download page, it reads the variables of do.php
     * @param string $canonical the address of the regular download page
     */
    function kj_amp_seo_show_page($canonical)
    {
        global $tpl, $config;

        $ads = kj_amp_seo_ads();

        $tpl->assign('amp_canonical', $canonical);
        $tpl->assign('amp_menu', kj_amp_seo_menu());
        $tpl->assign('amp_share', !empty($config['kj_amp_seo_share_buttons']));
        $tpl->assign('amp_ad_client', $ads['client']);
        $tpl->assign('amp_ad_top', $ads['top']);
        $tpl->assign('amp_ad_middle', $ads['middle']);
        $tpl->assign('amp_ad_sticky', $ads['sticky']);
        $tpl->assign('amp_has_ads', $ads['top'] || $ads['middle'] || $ads['sticky']);

        // the headers of Saaheader()
        header('Content-type: text/html; charset=UTF-8');
        header('Cache-Control: private, no-cache="set-cookie"');
        header('Pragma: no-cache');
        header('x-frame-options: SAMEORIGIN');
        header('x-xss-protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');

        echo $tpl->display('amp', __DIR__);
    }

    /**
     * the words of the guide on the help page, in the language of the admin,
     * a word that the translation misses is shown in English
     * @return array
     */
    function kj_amp_seo_help_words()
    {
        global $config;

        $words = (array) require __DIR__ . '/language/help_en.php';
        $language = preg_replace('/[^a-z0-9_-]/i', '', (string) ($config['language'] ?? ''));
        $translation = __DIR__ . "/language/help_{$language}.php";

        if ($language !== '' && $language !== 'en' && file_exists($translation)) {
            // in its own line, Prettier drops the brackets of (require $translation) + $words
            $translated = require $translation;
            $words = (array) $translated + $words;
        }

        return $words;
    }

    /**
     * a section of the guide from its numbered words, like KJ_AMP_SEO_HELP_TIP_1, KJ_AMP_SEO_HELP_TIP_2 ..
     * its title, when it has its own, is KJ_AMP_SEO_HELP_TIP_TITLE
     * @param  array  $words
     * @param  string $type  how Kleeja shows it: features, steps, tips, warnings or faq
     * @param  string $name  the name of its words, KJ_AMP_SEO_HELP_{name}_1
     * @return array
     */
    function kj_amp_seo_help_section($words, $type, $name)
    {
        $prefix = 'KJ_AMP_SEO_HELP_' . $name;
        $section = ['type' => $type, 'title' => $words[$prefix . '_TITLE'] ?? '', 'items' => []];

        for ($n = 1; isset($words[$prefix . ($type == 'faq' ? '_Q_' : '_') . $n]); $n++) {
            $section['items'][] =
                $type == 'faq'
                    ? ['q' => $words[$prefix . '_Q_' . $n], 'a' => $words[$prefix . '_A_' . $n] ?? '']
                    : $words[$prefix . '_' . $n];
        }

        return $section;
    }

    /**
     * the words of the plugin, they are added to the language table when it is installed or updated,
     * a <small> in the name of a setting is the hint under it
     * @return array
     */
    function kj_amp_seo_translations()
    {
        return [
            'en' => [
                'CONFIG_KLJ_MENUS_KJ_AMP_SEO' => 'AMP settings',
                'KJ_AMP_SEO_ENABLE' =>
                    'Enable AMP download pages <small>Gives every download page a fast AMP version and links to it from the head of the regular page, so search engines can find it.</small>',
                'KJ_AMP_SEO_SHARE_BUTTONS' =>
                    'Show share buttons <small>Adds buttons to share the download page on X (Twitter), Facebook, WhatsApp and Pinterest, or by email.</small>',
                'KJ_AMP_SEO_ADSENSE_CLIENT' =>
                    'AdSense publisher ID <small>The data-ad-client value of your ad code, like <span class="text-nowrap">ca-pub-1234567890123456</span>. Ads are shown only when it is set.</small>',
                'KJ_AMP_SEO_TOP_AD' =>
                    'Top ad slot ID <small>The data-ad-slot value of the ad unit shown above the file details. Leave it empty to show no ad there.</small>',
                'KJ_AMP_SEO_MIDDLE_AD' =>
                    'Middle ad slot ID <small>The data-ad-slot value of the ad unit shown above the download button. Leave it empty to show no ad there.</small>',
                'KJ_AMP_SEO_STICKY_AD' =>
                    'Sticky ad slot ID <small>The data-ad-slot value of a 320×50 ad unit, shown as a banner fixed to the bottom of the screen. Leave it empty to show no banner.</small>',
            ],
            'ar' => [
                'CONFIG_KLJ_MENUS_KJ_AMP_SEO' => 'خيارات صفحات AMP',
                'KJ_AMP_SEO_ENABLE' =>
                    'تفعيل صفحات AMP للتحميل <small>تُنشئ لكل صفحة تحميل نسخة AMP سريعة، وتضيف رابطها إلى ترويسة الصفحة العادية لتجدها محركات البحث.</small>',
                'KJ_AMP_SEO_SHARE_BUTTONS' =>
                    'إظهار أزرار المشاركة <small>تضيف أزرارًا لمشاركة صفحة التحميل على X (تويتر) وفيسبوك وواتساب وبنترست، أو عبر البريد الإلكتروني.</small>',
                'KJ_AMP_SEO_ADSENSE_CLIENT' =>
                    'معرّف الناشر في AdSense <small>قيمة data-ad-client في كود إعلانك، مثل <span dir="ltr" class="text-nowrap">ca-pub-1234567890123456</span>. لا تظهر الإعلانات إلا بعد إدخاله.</small>',
                'KJ_AMP_SEO_TOP_AD' =>
                    'معرّف الإعلان العلوي <small>قيمة data-ad-slot للوحدة الإعلانية التي تظهر فوق بيانات الملف. اتركه فارغًا لإخفاء هذا الإعلان.</small>',
                'KJ_AMP_SEO_MIDDLE_AD' =>
                    'معرّف الإعلان الأوسط <small>قيمة data-ad-slot للوحدة الإعلانية التي تظهر فوق زر التحميل. اتركه فارغًا لإخفاء هذا الإعلان.</small>',
                'KJ_AMP_SEO_STICKY_AD' =>
                    'معرّف الإعلان المثبّت <small>قيمة data-ad-slot لوحدة إعلانية بمقاس 320×50، تظهر شريطًا ثابتًا أسفل الشاشة. اتركه فارغًا لإخفاء الشريط.</small>',
            ],
        ];
    }
}
