<?php

namespace App\Controller;

use App\Entity\Places;
use App\Entity\Trainings;
use App\Form\TrainingsType;
use App\Repository\TrainingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/places')]
class PlacesController extends AbstractController
{
    #[Route('/{id<\d+>}/trainings', name: 'places_trainings')]
    public function index(Places $place, TrainingsRepository $trainingsRepository): Response
    {
        return $this->render('places/index.html.twig', [
            'place' => $place,
            'activeTrainings' => $trainingsRepository->findTrainingsByPlaceAndState($place, true),
            //'archivedTrainings' =>  $trainingsRepository->findTrainingsByPlaceAndState($place, false),
            'menuPlaces' => 'active'
        ]);
    }

    // TRAININGS

    #[Route('/{id<\d+>}/training/add/{tt<\d+>?0}', name: 'place_add_training')]
    public function addTopic(#[MapEntity(expr: 'repository.find(id)')] Places $place, int $tt, TrainingsRepository $trainingsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $training = new Trainings();
            $training->setPlace($place);
            $create = true;
        } else {
            $training = $trainingsRepository->findOneBy(['id'=> $tt]);
            if(empty($training) || $training->getPlace()->getId() !== $place->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        
        $form = $this->createForm(TrainingsType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($training);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('places_trainings', ['id' => $place->getId()]);
        }


        return $this->render('places/add_trainings.html.twig', [
            'place' => $place,
            'trainingForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[Route('/training/archive/{id}', name: 'place_archive_training')]
    public function removeTraining($id, TrainingsRepository $trainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $training = $trainingsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($training)) {
            $training->setInactive(new \DateTime('now'));
            $entityManager->persist($training);
            $entityManager->flush();
            return $this->redirectToRoute('places_trainings', ['id' => $training->getPlace()->getId()]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/training/reactivate/{id}', name: 'place_reactivate_training')]
    public function reactivateTraining($id, TrainingsRepository $trainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $training = $trainingsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($training)) {
            $training->setInactive(null);
            $entityManager->persist($training);
            $entityManager->flush();
            return $this->redirectToRoute('places_trainings', ['id' => $training->getPlace()->getId()]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}
