<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\SiteSettings;

/**
 * The community WhatsApp contact, built from SiteSettings so the number
 * lives in one admin-editable place instead of four Vue files. The join
 * form still only opens this link client-side — nothing is stored.
 */
final readonly class WhatsAppLink
{
    private function __construct(public string $number) {}

    public static function fromSettings(): ?self
    {
        $number = preg_replace('/\D+/', '', (string) (SiteSettings::data()['whatsapp_number'] ?? ''));

        return $number === '' ? null : new self($number);
    }

    public function url(?string $text = null): string
    {
        $base = "https://wa.me/{$this->number}";

        return $text === null ? $base : $base.'?text='.rawurlencode($text);
    }
}
