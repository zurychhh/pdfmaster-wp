<?php
/**
 * Email notifications for payment receipts.
 */

declare(strict_types=1);

namespace PDFMaster\Payments;

if (! defined('ABSPATH')) {
    exit;
}

class EmailHandler
{
    /**
     * Send payment receipt email.
     */
    public function send_receipt(int $user_id, array $payment_details): void
    {
        // TODO: Generate branded HTML emails with order summary and credit balance.
        unset($user_id, $payment_details);
    }
}
