<?php
declare(strict_types=1);

namespace MageSuite\BrandManagement\Model;

class UrlVerifier
{
    public const PATTERN = "~^(?:f|ht)tps?://~i";

    public function isExternalUrl(string $url)
    {
        return preg_match(self::PATTERN, $url);
    }
}
