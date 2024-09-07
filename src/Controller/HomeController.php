<?php

namespace App\Controller;

use App\Config\UsersStatusTrainingsEnum;
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

        // If we are on a teacher account, then redirect to user planning
        if($this->isGranted('ROLE_TEACHER'))
            return $this->redirectToRoute('profile_teacher_timetable');

        $sessionData = $session->get('AclPermissions'); 
        $nbTrainings = 0;
        $trainingId = 0;
        
        if(!empty($sessionData) && !empty($sessionData['trainings'])) {
            foreach($sessionData['trainings'] as $idT => $training) {
                if(!empty($training['status']) && !empty(UsersStatusTrainingsEnum::allowingPlatformAccess($training['status'])) ) {
                    $nbTrainings++;
                    $trainingId = $idT;
                }
            }
        }

        if($this->isGranted('ROLE_STUDENT') && empty($nbTrainings))
            return $this->redirectToRoute('profile');
        
        if($this->isGranted('ROLE_STUDENT'))
            return $this->redirectToRoute('training_timetable', ['training' => $trainingId]);

        return $this->render('home/index.html.twig', [
            'trainings' => $trainings->findAll(),
            'menuHome' => 'active'
        ]);
    }
}
