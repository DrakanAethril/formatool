<?php

namespace App\Controller;

use App\Entity\Trainings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingsController extends AbstractController
{
    #[Route('/training/{id}', name: 'training_detail')]
    public function detail(Trainings $training): Response
    {
        return $this->render('trainings/detail.html.twig', [
            'training' => $training,
        ]);
    }

    #[Route('/training/{id}/topic/add', name: 'training_add_topic')]
    public function addTopic(Trainings $training): Response
    {
        return $this->render('trainings/add_topic.html.twig', [
            'training' => $training,
        ]);
    }
}
