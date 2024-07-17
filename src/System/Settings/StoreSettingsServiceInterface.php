<?php declare(strict_types=1);

namespace App\System\Settings;

interface StoreSettingsServiceInterface
{
    public function isDoubleOptInAllowed(): bool;
}