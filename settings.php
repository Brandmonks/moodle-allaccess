<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_allaccess', get_string('pluginname', 'local_allaccess'));
    $ADMIN->add('localplugins', $settings);

    // Price.
    $settings->add(new admin_setting_configtext(
        'local_allaccess/price',
        get_string('price', 'local_allaccess'),
        '',
        '99.00',
        PARAM_RAW_TRIMMED
    ));

    // Currency from supported list (union of all payment gateways).
    $currencies = array_combine(\core_payment\helper::get_supported_currencies(),
        \core_payment\helper::get_supported_currencies());
    $settings->add(new admin_setting_configselect(
        'local_allaccess/currency',
        get_string('currency', 'local_allaccess'),
        '',
        'USD',
        $currencies
    ));

    // Payment account dropdown (site context).
    $accounts = \core_payment\helper::get_payment_accounts_menu(\context_system::instance());
    $settings->add(new admin_setting_configselect(
        'local_allaccess/accountid',
        get_string('accountid', 'local_allaccess'),
        get_string('accountid_desc', 'local_allaccess'),
        0,
        $accounts
    ));

    // Cohort dropdown (site-level cohorts).
    global $DB;
    $cohortmenu = [0 => '-'];
    foreach ($DB->get_records('cohort', null, 'name', 'id, name') as $c) {
        $cohortmenu[$c->id] = format_string($c->name);
    }
    $settings->add(new admin_setting_configselect(
        'local_allaccess/cohortid',
        get_string('cohortid', 'local_allaccess'),
        '',
        0,
        $cohortmenu
    ));

    // External buy website configuration.
    $settings->add(new admin_setting_configcheckbox(
        'local_allaccess/useexternal',
        get_string('useexternal', 'local_allaccess'),
        get_string('useexternal_desc', 'local_allaccess'),
        0
    ));

    $settings->add(new admin_setting_configtext(
        'local_allaccess/buyurl',
        get_string('buyurl', 'local_allaccess'),
        get_string('buyurl_desc', 'local_allaccess'),
        '',
        PARAM_URL
    ));
}
