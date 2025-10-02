<?php
/**
 * Credit tracking for PDFMaster.
 */

declare(strict_types=1);

namespace PDFMaster\Payments;

if (! defined('ABSPATH')) {
    exit;
}

class CreditsManager
{
    private const META_KEY = '_pdfm_credits';

    public function get_user_credits(int $user_id): int
    {
        $credits = get_user_meta($user_id, self::META_KEY, true);

        return (int) ($credits ?: 0);
    }

    public function add_credits(int $user_id, int $credits): void
    {
        $current = $this->get_user_credits($user_id);
        update_user_meta($user_id, self::META_KEY, max(0, $current + $credits));
    }

    public function deduct_credits(int $user_id, int $credits): bool
    {
        $current = $this->get_user_credits($user_id);

        if ($current < $credits) {
            return false;
        }

        update_user_meta($user_id, self::META_KEY, max(0, $current - $credits));

        return true;
    }
}
