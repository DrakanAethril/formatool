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
            self::SourceLesson  => 'Par heure de cours', //$translator->trans('Par heure de cours', locale: $locale),
            self::SourceStudent => 'Par étudiant',
            self::SourceManual  => 'Manuelle'
        };
    }
}