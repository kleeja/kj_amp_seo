<?php
//
// kj_amp_seo, its guide on the help page of the control panel
// English
//
// The guide is built by the admin_help_guides hook in init.php, the texts here can have HTML.
//  - a list is numbered: KJ_AMP_SEO_HELP_TIP_1, KJ_AMP_SEO_HELP_TIP_2 ... it ends at the first missing number,
//    so an item can be added or removed without touching the code
//  - KJ_AMP_SEO_HELP_{LIST}_TITLE is the title of the list, a list without it takes the title that Kleeja gives its type
//  - questions and answers are KJ_AMP_SEO_HELP_FAQ_Q_1 and KJ_AMP_SEO_HELP_FAQ_A_1
//  - names in <strong> are written as the control panel shows them: the words of Kleeja are in lang/en/,
//    the words of the plugin in kj_amp_seo_translations() of init.php
//

return [
    'KJ_AMP_SEO_HELP_TITLE' => 'AMP SEO',
    'KJ_AMP_SEO_HELP_INTRO' =>
        'Give every download page a fast AMP version. Search engines find it through a link in the head of the regular page, and visitors get a light page that opens quickly on phones, with share buttons and AdSense ads if you want them.',

    //
    // what the plugin does
    //
    'KJ_AMP_SEO_HELP_FEATURE_1' =>
        'Every download page has an AMP version at the same address with <code>amp_page=1</code> added, like <code>do.php?id=5&amp;amp_page=1</code> or <code>download5.html?amp_page=1</code>.',
    'KJ_AMP_SEO_HELP_FEATURE_2' =>
        'The regular page links to its AMP version with <code>&lt;link rel="amphtml"&gt;</code>, and the AMP version names the regular page as its canonical page, so search engines treat them as one page.',
    'KJ_AMP_SEO_HELP_FEATURE_3' =>
        'The AMP page shows the file name, type, size, upload date, number of downloads and uploader, the report link and the download button, with a menu of the pages of your site.',
    'KJ_AMP_SEO_HELP_FEATURE_4' =>
        'Optional buttons to share the download page on X (Twitter), Facebook, WhatsApp and Pinterest, or by email.',
    'KJ_AMP_SEO_HELP_FEATURE_5' =>
        'Up to three AdSense ads: one above the file details, one above the download button, and a banner fixed to the bottom of the screen.',

    //
    // how to use it
    //
    'KJ_AMP_SEO_HELP_STEP_TITLE' => 'Set up the AMP pages',
    'KJ_AMP_SEO_HELP_STEP_1' => 'Open <strong>Settings</strong>, then <strong>AMP settings</strong>.',
    'KJ_AMP_SEO_HELP_STEP_2' =>
        'Set <strong>Enable AMP download pages</strong> to <strong>Yes</strong>, and choose whether to <strong>Show share buttons</strong>.',
    'KJ_AMP_SEO_HELP_STEP_3' =>
        'To show ads, enter your <strong>AdSense publisher ID</strong> and the slot ID of each ad you want. A slot left empty shows no ad.',
    'KJ_AMP_SEO_HELP_STEP_4' => 'Click <strong>Update Settings</strong>.',
    'KJ_AMP_SEO_HELP_STEP_5' =>
        'Open the download page of a file, add <code>amp_page=1</code> to its address, and check the result.',

    //
    // common questions
    //
    'KJ_AMP_SEO_HELP_FAQ_Q_1' => 'Where do I find the publisher ID and the slot IDs?',
    'KJ_AMP_SEO_HELP_FAQ_A_1' =>
        'In the code that AdSense gives for an ad unit: the publisher ID is the <code>data-ad-client</code> value, like <code>ca-pub-1234567890123456</code>, and the slot ID is the <code>data-ad-slot</code> value, like <code>1234567890</code>. Create one ad unit for each place.',
    'KJ_AMP_SEO_HELP_FAQ_Q_2' => 'Why don\'t the ads show?',
    'KJ_AMP_SEO_HELP_FAQ_A_2' =>
        'The plugin shows an ad only when the publisher ID is <code>ca-pub-</code> followed by digits and the slot ID has digits only, so check both for spaces or extra characters. A new ad unit can also take some time before AdSense starts filling it.',
    'KJ_AMP_SEO_HELP_FAQ_Q_3' => 'Does the AMP page wait before showing the download button?',
    'KJ_AMP_SEO_HELP_FAQ_A_3' =>
        'No. AMP pages can\'t run the countdown script of the regular page, so the button is shown at once, whatever the <strong>Waiting period</strong> setting is.',
    'KJ_AMP_SEO_HELP_FAQ_Q_4' => 'How do I check that an AMP page is valid?',
    'KJ_AMP_SEO_HELP_FAQ_A_4' =>
        'Paste its address in the <a href="https://validator.ampproject.org/" target="_blank" rel="noopener">AMP Validator</a>, or open it with <code>#development=1</code> at the end of the address and read the browser console.',
    'KJ_AMP_SEO_HELP_FAQ_Q_5' => 'Does the plugin change my style?',
    'KJ_AMP_SEO_HELP_FAQ_A_5' =>
        'No. AMP pages can\'t load the CSS and scripts of a style, so the AMP page has its own small design inside the plugin. The regular download page only gets the amphtml link in its head.',

    //
    // beside the guide
    //
    'KJ_AMP_SEO_HELP_TIP_1' =>
        'Use a 320×50 ad unit for the sticky ad. The banner is at most 100 pixels tall, and a taller ad is cut off.',
    'KJ_AMP_SEO_HELP_TIP_2' =>
        'The share buttons share the address of the regular download page, so visitors who open a shared link see your style.',
    'KJ_AMP_SEO_HELP_TIP_3' =>
        'Google no longer ranks AMP pages above other pages. The gain is a page that opens fast on slow connections.',

    'KJ_AMP_SEO_HELP_WARNING_1' =>
        'When <strong>Enable AMP download pages</strong> is set to <strong>No</strong>, the amphtml link is removed and AMP addresses show the regular download page.',
    'KJ_AMP_SEO_HELP_WARNING_2' =>
        'Deleting the plugin deletes its settings, the AdSense IDs too. Write them down first if you will need them again.',

    //
    // in the guide of the settings, whose page has the tab of the plugin
    //
    'KJ_AMP_SEO_HELP_NOTE' =>
        'The <strong>AMP settings</strong> tab belongs to the <strong>AMP SEO</strong> plugin. <a href="#help-kj_amp_seo">Read its guide</a>.',
];
