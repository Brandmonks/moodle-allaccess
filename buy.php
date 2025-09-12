<?php
require(__DIR__ . '/../../config.php');
require_login();

$context = context_system::instance();
require_capability('local/allaccess:buy', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/allaccess/buy.php'));
$buytitle = trim((string)get_config('local_allaccess', 'buytitle'));
if ($buytitle === '') {
    $buytitle = get_string('buy', 'local_allaccess');
}
$PAGE->set_title($buytitle);
$PAGE->set_heading($buytitle);

$useexternal = (int)get_config('local_allaccess', 'useexternal');
$buyurl = trim((string)get_config('local_allaccess', 'buyurl'));
$buycontent = (string)get_config('local_allaccess', 'buycontent');

$cohortid = (int)get_config('local_allaccess', 'cohortid');
$already  = $cohortid && $DB->record_exists('cohort_members', ['cohortid' => $cohortid, 'userid' => $USER->id]);

echo $OUTPUT->header();

// Show quick edit link when edit mode is on and user can configure.
if ($PAGE->user_is_editing() && has_capability('local/allaccess:configure', $context)) {
    $editurl = new moodle_url('/local/allaccess/edit.php');
    echo html_writer::div(html_writer::link($editurl, get_string('editpage', 'local_allaccess'), [
        'class' => 'btn btn-secondary mb-3'
    ]));
}

if ($already) {
    echo $OUTPUT->notification(get_string('alreadyowned', 'local_allaccess'), 'notifysuccess');
} else {
    // Determine button HTML.
    $buybuttonhtml = '';
    if ($useexternal && !empty($buyurl)) {
        $buybuttonhtml = html_writer::link($buyurl, get_string('buy', 'local_allaccess'), [
            'class' => 'btn btn-primary',
            'target' => '_blank',
            'rel' => 'noopener'
        ]);
    } else {
        $attrs = \core_payment\helper::gateways_modal_link_params(
            'local_allaccess',
            'allaccess',
            1,
            get_string('description', 'local_allaccess')
        );
        $gateways = \core_payment\helper::get_available_gateways('local_allaccess', 'allaccess', 1);
        if (empty($gateways)) {
            echo $OUTPUT->notification(get_string('nogateways', 'local_allaccess'), 'notifyproblem');
        } else {
            $buybuttonhtml = html_writer::tag('button', get_string('buy', 'local_allaccess'),
                $attrs + ['class' => 'btn btn-primary']);
            $PAGE->requires->js_call_amd('core_payment/gateways_modal', 'init');
        }
    }

    // Content with optional placeholder.
    $content = $buycontent;
    if (trim($content) === '') {
        $content = get_string('buycontent_default', 'local_allaccess');
    }

    if (!empty($buybuttonhtml)) {
        if (preg_match('/\{\{\s*buybutton\s*\}\}/i', $content)) {
            $content = preg_replace('/\{\{\s*buybutton\s*\}\}/i', $buybuttonhtml, $content);
            echo html_writer::div(format_text($content, FORMAT_HTML, ['context' => $context, 'filter' => true, 'noclean' => true]));
        } else {
            echo html_writer::div(format_text($content, FORMAT_HTML, ['context' => $context, 'filter' => true, 'noclean' => true]));
            echo html_writer::div($buybuttonhtml, 'mt-3');
        }
    } else {
        // No button available; remove placeholder and show content.
        $content = preg_replace('/\{\{\s*buybutton\s*\}\}/i', '', $content);
        echo html_writer::div(format_text($content, FORMAT_HTML, ['context' => $context, 'filter' => true, 'noclean' => true]));
    }
}

echo $OUTPUT->footer();
