<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UsersRolesTrainingsEnum:string implements TranslatableInterface {

    use EnumListingTrait;
    
    case STUDENT = 'ROLE_STUDENT';
    case TEACHER = 'ROLE_TEACHER';
    case STAFF = 'ROLE_STAFF';
    case ADMIN = 'ROLE_ADMIN';
    case PEDAGOGIC_MANAGER = 'ROLE_PEDAGOGIC_MANAGER';
    case SCHOLARSHIP_MANAGER = 'ROLE_SCHOLARSHIP_MANAGER';
    case ADMINISTRATIVE_MANAGER = 'ROLE_ADMINISTRATIVE_MANAGER';

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            self::STUDENT  => 'Etudiant',
            self::TEACHER => 'Enseignant',
            self::STAFF => 'Personnel',
            self::ADMIN => 'Admin',
            self::PEDAGOGIC_MANAGER => 'Responsable pédagogique',
            self::SCHOLARSHIP_MANAGER => 'Responsable vie scolaire',
            self::ADMINISTRATIVE_MANAGER => 'Responsable administratif',
        };
    }

}