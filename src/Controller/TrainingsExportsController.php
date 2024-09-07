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
        if($this->isGranted(AclRessourcesEnum::TRAINING_EXPORTS_SIGNATURE->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_exports_signature', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_EXPORTS_INVOICING->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_exports_invoicing', ['training' => $training->getId()]);
        
        return $this->redirectToRoute('home');
    }

    #[Route('/{training<\d+>}/exports/signature', name: 'training_exports_signature')]
    #[IsGranted(AclRessourcesEnum::TRAINING_EXPORTS_SIGNATURE->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function signature(Trainings $training) {
        
        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings_exports/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'signature' 
        ]);
    }

    #[Route('/{training<\d+>}/exports/invoicing', name: 'training_exports_invoicing')]
    #[IsGranted(AclRessourcesEnum::TRAINING_EXPORTS_INVOICING->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function invoicing(Trainings $training) {
        
        return $this->render('trainings_exports/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'invoicing' 
        ]);
    }
}
