<?php

namespace App\Controller;

use App\Repository\TrainingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController

{
    #[Route('/', name: 'home')]
    public function index(TrainingsRepository $trainings, SessionInterface $session ): Response
    {
        $sessionData = $session->get('AclPermissions');
        $nbTrainings = 0;
        $trainingId = 0;
        if(!empty($sessionData) && !empty($sessionData['trainings'])) {
            foreach($sessionData['trainings'] as $idT => $training) {
                if(!empty($training['status']) && $training['status'] == 'ACTIVE') {
                    $nbTrainings++;
                    $trainingId = $idT;
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'trainings' => $trainings->findAll(),
            'menuHome' => 'active'
        ]);
    }
}
