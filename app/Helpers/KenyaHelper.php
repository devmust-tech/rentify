<?php

namespace App\Helpers;

class KenyaHelper
{
    /**
     * Normalize a Kenyan phone number to E.164 format (+254XXXXXXXXX).
     */
    public static function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($phone, '+254')) {
            return $phone;
        }
        if (str_starts_with($phone, '254')) {
            return '+' . $phone;
        }
        if (str_starts_with($phone, '0')) {
            return '+254' . substr($phone, 1);
        }

        return '+254' . $phone;
    }

    /**
     * Format amount as KES with thousands separator.
     */
    public static function formatKes(float|int $amount): string
    {
        return 'KSh ' . number_format($amount, 2);
    }

    /**
     * Get counties list for dropdowns.
     */
    public static function counties(): array
    {
        return config('counties.counties');
    }

    /**
     * Validate Kenyan mobile prefix.
     */
    public static function isValidKenyanPhone(string $phone): bool
    {
        $normalized = self::normalizePhone($phone);
        return (bool) preg_match('/^\+254[17]\d{8}$/', $normalized);
    }
}
