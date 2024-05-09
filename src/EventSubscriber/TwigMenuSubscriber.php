<?php

namespace App\EventSubscriber;

use App\Repository\PlacesRepository;
use App\Repository\TrainingsRepository;
use App\Repository\UsersRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class TwigMenuSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $trainingsRepository;
    private $usersRepository;
    private $userToken;
    private $user;

    public function __construct(Environment $twig, TrainingsRepository $trainingsRepository, UsersRepository $usersRepository, TokenStorageInterface $tokenStorageInterface)
    {
        $this->twig = $twig;
        $this->trainingsRepository = $trainingsRepository;
        $this->usersRepository = $usersRepository;
        $this->userToken = $tokenStorageInterface;
        if(!empty($this->userToken) && !empty($this->userToken->getToken())){
            $this->user = $tokenStorageInterface->getToken()->getUser();
        } else {
            $this->user = null;
        }
    }
    public function onKernelController(ControllerEvent $event): void
    {
        $allowedPlaces = $this->usersRepository->getAllowedPlacesForUser($this->user);
        $this->twig->addGlobal('menu_places', $allowedPlaces);

        $allowedTrainings = $this->usersRepository->getAllowedTrainingsForUser($this->user);
        $this->twig->addGlobal('menu_trainings', $this->trainingsRepository->findBy(['inactive' => null], ['title' => 'ASC']));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
