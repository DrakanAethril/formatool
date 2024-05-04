<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AclRessourcesEnum:string implements TranslatableInterface {

    use EnumListingTrait;

    // PLACE ADMIN
    case PLACE_ALL = 'PLACE_ALL';

    // PLACE PARAMS
    case PLACE_PARAMETERS = 'PLACE_PARAMETERS';
    case PLACE_PARAMETERS_CLASSROOM = 'PLACE_PARAMETERS_CLASSROOM';
    case PLACE_PARAMETERS_CURSUS = 'PLACE_PARAMETERS_CURSUS';
    case PLACE_PARAMETERS_USER = 'PLACE_PARAMETERS_USER';

    // TRAINING ADMIN
    case TRAINING_ALL = 'TRAINING_ALL';

    // TRAINING PARAMS
    case TRAINING_PARAMETERS = 'TRAINING_PARAMETERS';
    case TRAINING_PARAMETERS_TIMESLOT = 'TRAINING_PARAMETERS_TIMESLOT';
    case TRAINING_PARAMETERS_TOPIC_GROUP = 'TRAINING_PARAMETERS_TOPIC_GROUP';
    case TRAINING_PARAMETERS_TOPIC = 'TRAINING_PARAMETERS_TOPIC';
    case TRAINING_PARAMETERS_LESSON_SESSION = 'TRAINING_PARAMETERS_LESSON_SESSION';
    case TRAINING_PARAMETERS_FINANCIAL = 'TRAINING_PARAMETERS_FINANCIAL';
    case TRAINING_PARAMETERS_USER = 'TRAINING_PARAMETERS_USER';

    // TRAINING REPORTING
    case TRAINING_REPORTING_SCHOLARSHIP = 'TRAINING_REPORTING_SCHOLARSHIP';
    case TRAINING_REPORTING_PEDAGOGIC = 'TRAINING_REPORTING_PEDAGOGIC';
    case TRAINING_REPORTING_FINANCIAL = 'TRAINING_REPORTING_FINANCIAL';
    
    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {

            self::PLACE_ALL  => 'Accès à toute la structure',

            self::PLACE_PARAMETERS  => 'Paramétrage de la structure',
            self::PLACE_PARAMETERS_CLASSROOM => 'Paramétrage de la structure - Salles de classe',
            self::PLACE_PARAMETERS_CURSUS => 'Paramétrage de la structure - Cursus',
            self::PLACE_PARAMETERS_USER => 'Paramétrage de la structure - Utilisateurs',

            self::TRAINING_ALL => 'Accès à toute la formation',

            self::TRAINING_PARAMETERS  => 'Paramétrage de la formations',
            self::TRAINING_PARAMETERS_TIMESLOT => 'Paramétrage de la formation - Périodes',
            self::TRAINING_PARAMETERS_TOPIC_GROUP => 'Paramétrage de la formation - Unités d\'enseignement',
            self::TRAINING_PARAMETERS_TOPIC => 'Paramétrage de la formation - Matières',
            self::TRAINING_PARAMETERS_LESSON_SESSION => 'Paramétrage de la formation - Emploi du temps',
            self::TRAINING_PARAMETERS_FINANCIAL => 'Paramétrage de la formation - Finances',
            self::TRAINING_PARAMETERS_USER => 'Paramétrage de la formation - Utilisateurs',

            self::TRAINING_REPORTING_SCHOLARSHIP => 'Reporting de la formation - Vie scolaire',
            self::TRAINING_REPORTING_PEDAGOGIC => 'Reporting de la formation - Pédagogique',
            self::TRAINING_REPORTING_FINANCIAL => 'Reporting de la formation - Financier',

        };
    }
}