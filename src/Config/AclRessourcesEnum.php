<?php

namespace App\Config;

use App\Config\EnumListingTrait;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AclRessourcesEnum:string implements TranslatableInterface {

    use EnumListingTrait;

    // PLACE ADMIN
    case PLACE_ALL = 'PLACE_ALL';
    case PLACE_ALL_TRAININGS = 'PLACE_ALL_TRAININGS';

    // PLACE PARAMS
    case PLACE_PARAMETERS = 'PLACE_PARAMETERS';
    case PLACE_PARAMETERS_TRAINING = 'PLACE_PARAMETERS_TRAINING';
    case PLACE_PARAMETERS_CLASSROOM = 'PLACE_PARAMETERS_CLASSROOM';
    case PLACE_PARAMETERS_CURSUS = 'PLACE_PARAMETERS_CURSUS';
    case PLACE_PARAMETERS_USER = 'PLACE_PARAMETERS_USER';

    // PLACE EXPORTS
    case PLACE_EXPORTS = 'PLACE_EXPORTS';

    // TRAINING ADMIN
    case TRAINING_ALL = 'TRAINING_ALL';

    // TRAINING PARAMS
    case TRAINING_PARAMETERS = 'TRAINING_PARAMETERS';
    case TRAINING_PARAMETERS_OPTION = 'TRAINING_PARAMETERS_OPTION';
    case TRAINING_PARAMETERS_TIMESLOT = 'TRAINING_PARAMETERS_TIMESLOT';
    case TRAINING_PARAMETERS_TOPIC_GROUP = 'TRAINING_PARAMETERS_TOPIC_GROUP';
    case TRAINING_PARAMETERS_TOPIC = 'TRAINING_PARAMETERS_TOPIC';
    case TRAINING_PARAMETERS_LESSON_SESSION = 'TRAINING_PARAMETERS_LESSON_SESSION';
    case TRAINING_PARAMETERS_FINANCIAL = 'TRAINING_PARAMETERS_FINANCIAL';
    case TRAINING_PARAMETERS_USER = 'TRAINING_PARAMETERS_USER';
    

    // TRAINING REPORTING
    case TRAINING_REPORTING = 'TRAINING_REPORTING';
    case TRAINING_REPORTING_SCHOLARSHIP = 'TRAINING_REPORTING_SCHOLARSHIP';
    case TRAINING_REPORTING_PEDAGOGIC = 'TRAINING_REPORTING_PEDAGOGIC';
    case TRAINING_REPORTING_FINANCIAL = 'TRAINING_REPORTING_FINANCIAL';

    // TRAINING EXPORTS
    case TRAINING_EXPORTS = 'TRAINING_EXPORTS';
    case TRAINING_EXPORTS_SIGNATURE = 'TRAINING_EXPORTS_SIGNATURE';
    case TRAINING_EXPORTS_INVOICING = 'TRAINING_EXPORTS_INVOICING';
    case TRAINING_EXPORTS_REPORT = 'TRAINING_EXPORTS_REPORT';
    case TRAINING_EXPORTS_TSF = 'TRAINING_EXPORTS_TSF';
    
    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        //return $translator->trans($this->name, locale: $locale); //ie: SourceLesson
        
        // Translate enum using custom labels
        return match ($this) {

            self::PLACE_ALL  => 'Accès à toute la structure',
            self::PLACE_ALL_TRAININGS  => 'Accès à toutes les formations de la structure',

            self::PLACE_PARAMETERS  => 'Paramétrage de la structure',
            self::PLACE_PARAMETERS_TRAINING => 'Paramétrage de la structure - Formations', //allowed to all that can see the place for the read privilege.
            self::PLACE_PARAMETERS_CLASSROOM => 'Paramétrage de la structure - Salles de classe',
            self::PLACE_PARAMETERS_CURSUS => 'Paramétrage de la structure - Cursus',
            self::PLACE_PARAMETERS_USER => 'Paramétrage de la structure - Utilisateurs',

            self::PLACE_EXPORTS => 'Exports pour la structure',

            self::TRAINING_ALL => 'Accès à toute la formation',

            self::TRAINING_PARAMETERS  => 'Paramétrage de la formations',
            self::TRAINING_PARAMETERS_OPTION => 'Paramétrage de la formation - Options',
            self::TRAINING_PARAMETERS_TIMESLOT => 'Paramétrage de la formation - Périodes',
            self::TRAINING_PARAMETERS_TOPIC_GROUP => 'Paramétrage de la formation - Unités d\'enseignement',
            self::TRAINING_PARAMETERS_TOPIC => 'Paramétrage de la formation - Matières',
            self::TRAINING_PARAMETERS_LESSON_SESSION => 'Paramétrage de la formation - Emploi du temps',
            self::TRAINING_PARAMETERS_FINANCIAL => 'Paramétrage de la formation - Finances',
            self::TRAINING_PARAMETERS_USER => 'Paramétrage de la formation - Utilisateurs',

            self::TRAINING_REPORTING  => 'Reporting de la formations',
            self::TRAINING_REPORTING_SCHOLARSHIP => 'Reporting de la formation - Vie scolaire',
            self::TRAINING_REPORTING_PEDAGOGIC => 'Reporting de la formation - Pédagogique',
            self::TRAINING_REPORTING_FINANCIAL => 'Reporting de la formation - Financier',

            self::TRAINING_EXPORTS => 'Exports pour la formation',
            self::TRAINING_EXPORTS_SIGNATURE => 'Listes d\'émargement',
            self::TRAINING_EXPORTS_INVOICING => 'Heures facturables',
            self::TRAINING_EXPORTS_REPORT => 'Comptes Rendus',
            self::TRAINING_EXPORTS_TSF => 'TSF'

        };
    }
}