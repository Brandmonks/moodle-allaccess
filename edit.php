<?php
require(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();
require_capability('local/allaccess:configure', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/allaccess/edit.php'));
$PAGE->set_title(get_string('editpagecontent', 'local_allaccess'));
$PAGE->set_heading(get_string('editpagecontent', 'local_allaccess'));

// Form.
require_once(__DIR__ . '/classes/form/buycontent_form.php');
$mform = new \local_allaccess\form\buycontent_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/allaccess/buy.php'));
}

if ($data = $mform->get_data()) {
    $text = is_array($data->content) ? ($data->content['text'] ?? '') : (string)$data->content;
    set_config('buycontent', $text, 'local_allaccess');
    redirect(new moodle_url('/local/allaccess/buy.php'), get_string('contentupdated', 'local_allaccess'));
}

// Defaults.
$current = (string)get_config('local_allaccess', 'buycontent');
if (trim($current) === '') {
    $current = get_string('buycontent_default', 'local_allaccess');
}
$mform->set_data(['content' => ['text' => $current, 'format' => FORMAT_HTML]]);

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
