<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AclPrivilegesEnum:string implements TranslatableInterface {

    use EnumListingTrait;

    //case CREATE = 'CREATE';
    case READ = 'READ';
    //case UPDATE = 'UPDATE';
    CASE WRITE = 'WRITE';
    case DELETE = 'DELETE';
    case ALL = 'ALL';

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            //self::CREATE  => 'Création',
            self::READ  => 'Lecture',
            //self::UPDATE => 'Mise à jour',
            self::WRITE => 'Ecriture',
            self::DELETE => 'Suppression',
            self::ALL => 'Tous les droits'
        };
    }
}