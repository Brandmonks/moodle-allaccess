<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/allaccess:buy' => [
        'captype'      => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => ['guest' => CAP_ALLOW, 'user' => CAP_ALLOW],
    ],
    'local/allaccess:configure' => [
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => ['manager' => CAP_ALLOW],
    ],
];
