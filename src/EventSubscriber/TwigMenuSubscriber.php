<?php

namespace App\EventSubscriber;

use App\Repository\TrainingsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigMenuSubscriber implements EventSubscriberInterface
{
    private $twig;
   private $trainingsRepository;

   public function __construct(Environment $twig, TrainingsRepository $trainingsRepository)
   {
        $this->twig = $twig;
        $this->trainingsRepository = $trainingsRepository;
   }
    public function onKernelController(ControllerEvent $event): void
    {
        $this->twig->addGlobal('menu_trainings', $this->trainingsRepository->findBy(['inactive' => null], ['title' => 'ASC']));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
