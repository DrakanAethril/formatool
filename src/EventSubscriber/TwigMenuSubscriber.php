<?php

namespace App\EventSubscriber;

use App\Repository\PlacesRepository;
use App\Repository\TrainingsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigMenuSubscriber implements EventSubscriberInterface
{
    private $twig;
   private $trainingsRepository;
   private $placesRepository;

   public function __construct(Environment $twig, TrainingsRepository $trainingsRepository, PlacesRepository $placesRepository)
   {
        $this->twig = $twig;
        $this->trainingsRepository = $trainingsRepository;
        $this->placesRepository = $placesRepository;
   }
    public function onKernelController(ControllerEvent $event): void
    {
        $this->twig->addGlobal('menu_trainings', $this->trainingsRepository->findBy(['inactive' => null], ['title' => 'ASC']));
        $this->twig->addGlobal('menu_places', $this->placesRepository->findBy(['inactive' => null], ['name' => 'ASC']));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
