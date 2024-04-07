<?php

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum FinancialItemsSourceEnum:int implements TranslatableInterface {
    case SourceLesson = 1;
    case SourceStudent = 2;
    case SourceManual = 3;

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            self::SourceLesson  => 'Par type de cours', //$translator->trans('Par type de cours', locale: $locale),
            self::SourceStudent => 'Par étudiant',
            self::SourceManual  => 'Manuelle'
        };
    }
}

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