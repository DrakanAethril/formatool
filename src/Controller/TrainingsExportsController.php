<?php

namespace App\Controller;

use App\Config\AclPrivilegesEnum;
use App\Config\AclRessourcesEnum;
use App\Entity\Trainings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('app/trainings')]

class TrainingsExportsController extends AbstractController
{
    // EXPORTS - DEFAULT TO SIGNATURE TABS

    #[Route('/{training<\d+>}/exports', name: 'training_exports_routing')]
    #[IsGranted(AclRessourcesEnum::TRAINING_EXPORTS->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function exportsRouting(Trainings $training) {
        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TIMESLOT->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_timeslots', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC_GROUP->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_topics_groups', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_topics', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_LESSON_SESSION->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_timetable', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_FINANCIAL->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_financial', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_OPTION->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_options', ['training' => $training->getId()]);
        
            return $this->redirectToRoute('home');

    }
}
