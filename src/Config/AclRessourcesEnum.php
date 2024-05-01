<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AclRessourcesEnum:string implements TranslatableInterface {

    use EnumListingTrait;


    case TRAINING_PARAMETERS = 'TRAINING_PARAMETERS';
    case TRAINING_REPORTING = 'TRAINING_REPORTING';
    case USERS = 'USERS';

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            self::TRAINING_PARAMETERS  => 'Paramétrage des formations',
            self::TRAINING_REPORTING => 'Reporting des formations',
            self::USERS => 'Gestion des utilisateurs',
        };
    }
}