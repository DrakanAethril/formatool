<?php

namespace App\Controller;

use App\Repository\TrainingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/dashboard', name: 'home')]
    public function index(TrainingsRepository $trainings ): Response
    {
        return $this->render('home/index.html.twig', [
            'trainings' => $trainings->findAll(),
            'menuHome' => 'active'
        ]);
    }
}
