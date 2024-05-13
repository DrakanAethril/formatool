<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UsersRolesPlacesEnum:string implements TranslatableInterface {

    use EnumListingTrait;
    
    case TEACHER = 'ROLE_TEACHER';
    case STAFF = 'ROLE_STAFF';
    case MANAGEMENT = 'ROLE_MANAGEMENT';
    case ADMIN = 'ROLE_ADMIN';

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            self::TEACHER => 'Enseignant',
            self::STAFF => 'Personnel',
            self::MANAGEMENT => 'Direction',
            self::ADMIN => 'Admin',

        };
    }

}