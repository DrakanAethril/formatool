<?php

namespace App\Controller;

use App\Entity\Trainings;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/training/{training<\d+>}/reporting')]

class TrainingsReportingController extends AbstractController
{
    #[Route('/', name: 'training_reporting')]
    #[Route('/pedagogic', name: 'training_reporting_pedagogic')]
    public function pedagogic(#[MapEntity(expr: 'repository.find(training)')] Trainings $training): Response
    {
        return $this->render('trainings_reporting/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'pedagogic'
        ]);
    }

    #[Route('/financial', name: 'training_reporting_financial')]
    public function financial(#[MapEntity(expr: 'repository.find(training)')] Trainings $training): Response
    {
        return $this->render('trainings_reporting/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'financial'
        ]);
    }
}


