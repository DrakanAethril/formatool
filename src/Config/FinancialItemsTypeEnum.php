<?php

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum FinancialItemsTypeEnum: int implements TranslatableInterface {
    case TypeGain = 1;
    case TypeCost = -1;

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum using custom labels
        return match ($this) {
            self::TypeGain  => 'Gain',
            self::TypeCost => 'Coût'
        };
    }
}