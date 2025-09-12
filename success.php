<?php
require(__DIR__ . '/../../config.php');
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/allaccess/success.php'));
$PAGE->set_title(get_string('success', 'local_allaccess'));
$PAGE->set_heading(get_string('success', 'local_allaccess'));

echo $OUTPUT->header();
echo $OUTPUT->notification(get_string('success', 'local_allaccess'), 'notifysuccess');
echo $OUTPUT->continue_button(new moodle_url('/my/courses.php'));
echo $OUTPUT->footer();
