<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrainingsExportsController extends AbstractController
{
    #[Route('/trainings/exports', name: 'app_trainings_exports')]
    public function index(): Response
    {
        return $this->render('trainings_exports/index.html.twig', [
            'controller_name' => 'TrainingsExportsController',
        ]);
    }
}
