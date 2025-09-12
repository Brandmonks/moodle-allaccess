<?php
namespace local_allaccess\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class buycontent_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('editor', 'content', get_string('content', 'local_allaccess'), null, [
            'maxfiles' => 0,
            'trusttext' => true,
            'subdirs' => false,
        ]);
        $mform->setType('content', PARAM_RAW);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}

