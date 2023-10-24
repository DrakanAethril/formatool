<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeachersController extends AbstractController
{
    #[Route('/teachers', name: 'teachers')]
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('teachers/index.html.twig', [
            'teachers' => $usersRepository->findAllByRole('ROLE_TEACHER')
        ]);
    }
}
