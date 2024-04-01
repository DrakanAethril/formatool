<?php

namespace App\Controller;

use App\Entity\ClassRooms;
use App\Entity\Cursus;
use App\Entity\Places;
use App\Entity\Trainings;
use App\Form\ClassRoomType;
use App\Form\CursusFormType;
use App\Form\TrainingsType;
use App\Repository\ClassRoomsRepository;
use App\Repository\CursusRepository;
use App\Repository\TrainingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

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
    public function addTraining(#[MapEntity(expr: 'repository.find(id)')] Places $place, int $tt, TrainingsRepository $trainingsRepository, Request $request, EntityManagerInterface $entityManager): Response
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

    // CLASSROOMS

    #[Route('/{id<\d+>}/classroom/add/{tt<\d+>?0}', name: 'place_add_class_room')]
    public function addClassRoom(#[MapEntity(expr: 'repository.find(id)')] Places $place, int $tt, ClassRoomsRepository $classRoomsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $classRoom = new ClassRooms();
            $classRoom->setPlace($place);
            $create = true;
        } else {
            $classRoom = $classRoomsRepository->findOneBy(['id'=> $tt]);
            if(empty($classRoom) || $classRoom->getPlace()->getId() !== $place->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(ClassRoomType::class, $classRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($classRoom);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('places_parameters', ['id' => $place->getId()]);
        }


        return $this->render('places/add_classroom.html.twig', [
            'place' => $place,
            'classRoomForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[Route('/classroom/remove/{id}', name: 'place_remove_class_room')]
    public function removeClassRoom($id, ClassRoomsRepository $classRoomsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $classRoom = $classRoomsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($classRoom)) {
            $idPlace = $classRoom->getPlace()->getId();
            $classRoom->setInactive(new \DateTime());
            $entityManager->persist($classRoom);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters', ['id' => $idPlace]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // CURSUSES

    #[Route('/{id<\d+>}/cursus/add/{tt<\d+>?0}', name: 'place_add_cursus')]
    public function addCursus(#[MapEntity(expr: 'repository.find(id)')] Places $place, int $tt, CursusRepository $cursusRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $cursus = new Cursus();
            $cursus->addPlace($place);
            $create = true;
        } else {
            $cursus = $cursusRepository->findOneBy(['id'=> $tt]);
            if(empty($cursus)) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(CursusFormType::class, $cursus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cursus);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('places_parameters_cursuses', ['id' => $place->getId()]);
        }


        return $this->render('places/add_cursus.html.twig', [
            'place' => $place,
            'cursusForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[Route('/{place<\d+>}cursus/remove/{id}', name: 'place_remove_cursus')]
    public function removeCursus(#[MapEntity(expr: 'repository.find(place)')] Places $place, $id, CursusRepository $cursusRepository, EntityManagerInterface $entityManager) : Response
    {   
        $cursus = $cursusRepository->findOneBy(['id' => intval($id)]);
        if(!empty($cursus)) {
            $idPlace = $place->getId();
            $cursus->setInactive(new \DateTime());
            $entityManager->persist($cursus);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters_cursuses', ['id' => $idPlace]);
        } else {
            return $this->redirectToRoute('home');
        }
    }


    // PARAMETERS - DEFAULT TO ROOMS TABS

    #[Route('/{id<\d+>}/parameters', name: 'places_parameters')]
    public function parametersRooms(Places $place): Response
    {

        if(empty($place))
        return $this->redirectToRoute('home');

        return $this->render('places/parameters.html.twig', [
            'place' => $place,
            'menuPlaces' => 'active',
            'currentTab' => 'rooms' 
        ]);
    }

    #[Route('/{id<\d+>}/parameters/cursuses', name: 'places_parameters_cursuses')]
    public function parametersCursuses(Places $place): Response
    {

        if(empty($place))
        return $this->redirectToRoute('home');

        return $this->render('places/parameters.html.twig', [
            'place' => $place,
            'menuPlaces' => 'active',
            'currentTab' => 'cursuses' 
        ]);
    }
}
