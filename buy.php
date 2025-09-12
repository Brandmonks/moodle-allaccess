<?php
require(__DIR__ . '/../../config.php');
require_login();

$context = context_system::instance();
require_capability('local/allaccess:buy', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/allaccess/buy.php'));
$PAGE->set_title(get_string('buy', 'local_allaccess'));
$PAGE->set_heading(get_string('buy', 'local_allaccess'));

$useexternal = (int)get_config('local_allaccess', 'useexternal');
$buyurl = trim((string)get_config('local_allaccess', 'buyurl'));

$cohortid = (int)get_config('local_allaccess', 'cohortid');
$already  = $cohortid && $DB->record_exists('cohort_members', ['cohortid' => $cohortid, 'userid' => $USER->id]);

echo $OUTPUT->header();

if ($already) {
    echo $OUTPUT->notification(get_string('alreadyowned', 'local_allaccess'), 'notifysuccess');
} else {
    if ($useexternal && !empty($buyurl)) {
        // Render a button to external purchase site (admin-configured).
        echo html_writer::link($buyurl, get_string('buy', 'local_allaccess'), [
            'class' => 'btn btn-primary',
            'target' => '_blank',
            'rel' => 'noopener'
        ]);
    } else {
        // Build data-attrs for the payment modal trigger (Moodle core payments).
        $attrs = \core_payment\helper::gateways_modal_link_params(
            'local_allaccess',   // component
            'allaccess',         // payment area
            1,                   // itemid (fixed product)
            get_string('description', 'local_allaccess') // description
        );

        $gateways = \core_payment\helper::get_available_gateways('local_allaccess', 'allaccess', 1);
        if (empty($gateways)) {
            echo $OUTPUT->notification(get_string('nogateways', 'local_allaccess'), 'notifyproblem');
        } else {
            echo html_writer::tag('button', get_string('buy', 'local_allaccess'),
                $attrs + ['class' => 'btn btn-primary']);
            // Boot the modal JS.
            $PAGE->requires->js_call_amd('core_payment/gateways_modal', 'init');
        }
    }
}

echo $OUTPUT->footer();
