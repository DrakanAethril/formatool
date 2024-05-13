<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UsersStatusPlacesEnum:string implements TranslatableInterface {

    use EnumListingTrait;
    
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case PENDING = 'PENDING';
    case REFUSED = 'REFUSED';

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            self::ACTIVE  => 'Actif',
            self::INACTIVE => 'Désactivé',
            self::PENDING => 'En attente',
            self::REFUSED => 'Refusé',
            
        };
    }

}