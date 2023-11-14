<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentRegistrationController extends AbstractController
{
    #[Route('/student/registration', name: 'app_student_registration')]
    public function index(): Response
    {
        return $this->render('student_registration/index.html.twig', [
            'controller_name' => 'StudentRegistrationController',
        ]);
    }
}
