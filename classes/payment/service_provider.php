<?php

namespace local_allaccess\payment;

use core_payment\local\entities\payable;

defined('MOODLE_INTERNAL') || die();

/**
 * Payment callbacks for local_allaccess.
 */
class service_provider implements \core_payment\local\callback\service_provider
{

    public static function get_payable(string $paymentarea, int $itemid): payable
    {
        if ($paymentarea !== 'allaccess' || $itemid !== 1) {
            throw new \moodle_exception('invalidaccess', 'error');
        }
        $amount = (float)get_config('local_allaccess', 'price');
        $currency = (string)get_config('local_allaccess', 'currency');
        $account = (int)get_config('local_allaccess', 'accountid');
        if (!$amount || !$currency || !$account) {
            throw new \moodle_exception('configmissing', 'error');
        }
        return new payable($amount, $currency, $account);
    }

    public static function deliver_order(string $paymentarea, int $itemid, int $paymentid, int $userid): bool
    {
        global $CFG, $DB;
        if ($paymentarea !== 'allaccess') {
            return false;
        }
        $cohortid = (int)get_config('local_allaccess', 'cohortid');
        if (!$cohortid) {
            return false;
        }
        require_once($CFG->dirroot . '/cohort/lib.php');
        // Idempotent: don’t error if already a member.
        if (!$DB->record_exists('cohort_members', ['cohortid' => $cohortid, 'userid' => $userid])) {
            cohort_add_member($cohortid, $userid);
        }
        return true;
    }

    // Nice UX: send buyers somewhere useful after payment succeeds.
    public static function get_success_url(string $paymentarea, int $itemid): \moodle_url
    {
        return new \moodle_url('/local/allaccess/success.php');
    }
}
