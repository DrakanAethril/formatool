<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AclPrivilegesEnum:string implements TranslatableInterface {

    use EnumListingTrait;

    case READ = 'READ';
    case WRITE = 'WRITE';
    case DELETE = 'DELETE';

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            self::READ  => 'Lecture',
            self::WRITE => 'Ecriture',
            self::DELETE => 'Suppression'
        };
    }
}