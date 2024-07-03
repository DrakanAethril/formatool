<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UsersStatusTrainingsEnum:string implements TranslatableInterface {

    use EnumListingTrait;
    
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case WAITING_CONTRACT = 'WAITING_CONTRACT';
    case WAITING_FILE = 'WAITING_FILE';
    case WAITING_INTERNSHIP = 'WAITING_INTERNSHIP';
    case PENDING = 'PENDING';
    case REFUSED = 'REFUSED';

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {
            self::ACTIVE  => 'Validé',
            self::INACTIVE => 'Désactivé',
            self::WAITING_CONTRACT => 'En attente de contrat',
            self::WAITING_FILE => 'Dossier incomplet',
            self::WAITING_INTERNSHIP => 'Recherche alternance',
            self::PENDING => 'En attente',
            self::REFUSED => 'Refusé',
            
        };
    }

}