<?php

namespace App\Controller;

use App\Config\AclPrivilegesEnum;
use App\Config\AclRessourcesEnum;
use App\Config\UsersRolesPlacesEnum;
use App\Config\UsersStatusPlacesEnum;
use App\Entity\ClassRooms;
use App\Entity\Cursus;
use App\Entity\Places;
use App\Entity\Trainings;
use App\Entity\UsersPlaces;
use App\Form\ClassRoomType;
use App\Form\CursusFormType;
use App\Form\TrainingsType;
use App\Form\UsersPlacesType;
use App\Repository\ClassRoomsRepository;
use App\Repository\CursusRepository;
use App\Repository\TrainingsRepository;
use App\Repository\UsersPlacesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('app/places')]
class PlacesController extends AbstractController
{
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_TRAINING->value.'|'.AclPrivilegesEnum::READ->value, 'place')]
    #[Route('/{place<\d+>}/trainings', name: 'places_trainings')]
    public function trainings(Places $place, TrainingsRepository $trainingsRepository): Response
    {
        return $this->render('places/index.html.twig', [
            'place' => $place,
            'activeTrainings' => $trainingsRepository->findTrainingsByPlaceAndState($place, true),
            //'archivedTrainings' =>  $trainingsRepository->findTrainingsByPlaceAndState($place, false),
            'menuPlaces' => 'active'
        ]);
    }

    // TRAININGS

    #[Route('/{place<\d+>}/training/add/{tt<\d+>?0}', name: 'place_add_training')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_TRAINING->value.'|'.AclPrivilegesEnum::WRITE->value, 'place')]
    public function addTraining(#[MapEntity(expr: 'repository.find(place)')] Places $place, int $tt, TrainingsRepository $trainingsRepository, Request $request, EntityManagerInterface $entityManager): Response
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
        
        $form = $this->createForm(TrainingsType::class, $training, [
            'place' => $place
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($training);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('places_trainings', ['place' => $place->getId()]);
        }


        return $this->render('places/add_trainings.html.twig', [
            'place' => $place,
            'trainingForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[Route('/{place<\d+>}/training/archive/{training}', name: 'place_archive_training')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_TRAINING->value.'|'.AclPrivilegesEnum::DELETE->value, 'place')]
    public function removeTraining(#[MapEntity(expr: 'repository.find(place)')] Places $place, $training, TrainingsRepository $trainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $training = $trainingsRepository->findOneBy(['id' => intval($training)]);
        if(!empty($training) && $training->getPlace()->getId() == $place->getId()) {
            $training->setInactive(new \DateTime('now'));
            $entityManager->persist($training);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters_trainings', ['place' => $training->getPlace()->getId()]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/{place<\d+>}/training/reactivate/{training}', name: 'place_reactivate_training')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_TRAINING->value.'|'.AclPrivilegesEnum::DELETE->value, 'place')]
    public function reactivateTraining(#[MapEntity(expr: 'repository.find(place)')] Places $place, $training, TrainingsRepository $trainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $training = $trainingsRepository->findOneBy(['id' => intval($training)]);
        if(!empty($training) && $training->getPlace()->getId() == $place->getId()) {
            $training->setInactive(null);
            $entityManager->persist($training);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters_trainings', ['place' => $training->getPlace()->getId()]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // CLASSROOMS

    #[Route('/{place<\d+>}/classroom/add/{tt<\d+>?0}', name: 'place_add_class_room')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_CLASSROOM->value.'|'.AclPrivilegesEnum::WRITE->value, 'place')]
    public function addClassRoom(#[MapEntity(expr: 'repository.find(place)')] Places $place, int $tt, ClassRoomsRepository $classRoomsRepository, Request $request, EntityManagerInterface $entityManager): Response
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
            return $this->redirectToRoute('places_parameters', ['place' => $place->getId()]);
        }


        return $this->render('places/add_classroom.html.twig', [
            'place' => $place,
            'classRoomForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[Route('/{place<\d+>}/classroom/remove/{id}', name: 'place_remove_class_room')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_CLASSROOM->value.'|'.AclPrivilegesEnum::DELETE->value, 'place')]
    public function removeClassRoom(#[MapEntity(expr: 'repository.find(place)')] Places $place, $id, ClassRoomsRepository $classRoomsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $classRoom = $classRoomsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($classRoom) && $classRoom->getPlace()->getId() == $place->getId()) {
            $idPlace = $classRoom->getPlace()->getId();
            $classRoom->setInactive(new \DateTime());
            $entityManager->persist($classRoom);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters', ['place' => $idPlace]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // CURSUSES

    #[Route('/{place<\d+>}/cursus/add/{tt<\d+>?0}', name: 'place_add_cursus')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_CURSUS->value.'|'.AclPrivilegesEnum::WRITE->value, 'place')]
    public function addCursus(#[MapEntity(expr: 'repository.find(place)')] Places $place, int $tt, CursusRepository $cursusRepository, Request $request, EntityManagerInterface $entityManager): Response
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
            return $this->redirectToRoute('places_parameters_cursuses', ['place' => $place->getId()]);
        }


        return $this->render('places/add_cursus.html.twig', [
            'place' => $place,
            'cursusForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[Route('/{place<\d+>}/cursus/remove/{id}', name: 'place_remove_cursus')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_CURSUS->value.'|'.AclPrivilegesEnum::DELETE->value, 'place')]
    public function removeCursus(#[MapEntity(expr: 'repository.find(place)')] Places $place, $id, CursusRepository $cursusRepository, EntityManagerInterface $entityManager) : Response
    {   
        $cursus = $cursusRepository->findOneBy(['id' => intval($id)]);
        if(!empty($cursus)) {
            $idPlace = $place->getId();
            $cursus->setInactive(new \DateTime());
            $entityManager->persist($cursus);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters_cursuses', ['place' => $idPlace]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // USERS

    #[Route('/{place<\d+>}/user/add/{tt<\d+>?0}', name: 'place_add_person')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::WRITE->value, 'place')]
    public function addPerson(#[MapEntity(expr: 'repository.find(place)')] Places $place, int $tt, UsersPlacesRepository $usersPlacesRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $userPlaces = new UsersPlaces();
            $userPlaces->setPlace($place);
            $create = true;
            $roles = [];
            $status = null;
        } else {
            $userPlaces = $usersPlacesRepository->findOneBy(['id'=> $tt]);
            $status = UsersStatusPlacesEnum::from($userPlaces->getStatus());
            $roles = [];
            if(!empty($userPlaces->getRoles())) {
                foreach($userPlaces->getRoles() as $role) {
                    $roles[] = UsersRolesPlacesEnum::from($role);
                }
            }
            
            if(empty($userPlaces)) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(UsersPlacesType::class, $userPlaces, [
            'status' => $status,
            'roles' => $roles,
            'create' => $create
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userPlaces->setStatus($form->get('statusEntry')->getData()->value);
            $newRoles = [];
            foreach($form->get('rolesEntry')->getData() as $role) {
                $newRoles[] = $role->value;
            }
            $userPlaces->setRoles($newRoles);
            $entityManager->persist($userPlaces);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('places_parameters_people', ['place' => $place->getId()]);
        }


        return $this->render('places/add_people.html.twig', [
            'place' => $place,
            'userForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[Route('/{place<\d+>}/user/remove/{id}', name: 'place_remove_person')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::DELETE->value, 'place')]
    public function removePerson(#[MapEntity(expr: 'repository.find(place)')] Places $place, $id, UsersPlacesRepository $usersPlacesRepository, EntityManagerInterface $entityManager) : Response
    {   
        $userPlaces = $usersPlacesRepository->findOneBy(['id' => intval($id)]);
        if(!empty($userPlaces) && $userPlaces->getPlace()->getId() == $place->getId()) {
            $idPlace = $place->getId();
            $userPlaces->setStatus(UsersStatusPlacesEnum::INACTIVE->value);
            $entityManager->persist($userPlaces);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters_people', ['place' => $idPlace]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/{place<\d+>}/user/reactivate/{id}', name: 'place_reactivate_person')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::WRITE->value, 'place')]
    public function reactivatePerson(#[MapEntity(expr: 'repository.find(place)')] Places $place, $id, UsersPlacesRepository $usersPlacesRepository, EntityManagerInterface $entityManager) : Response
    {   
        $userPlaces = $usersPlacesRepository->findOneBy(['id' => intval($id)]);
        if(!empty($userPlaces) && $userPlaces->getPlace()->getId() == $place->getId()) {
            $idPlace = $place->getId();
            $userPlaces->setStatus(UsersStatusPlacesEnum::ACTIVE->value);
            $entityManager->persist($userPlaces);
            $entityManager->flush();
            return $this->redirectToRoute('places_parameters_people', ['place' => $idPlace]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // PARAMETERS - DEFAULT TO TRAININGS TABS

    #[Route('/{place<\d+>}/parameters', name: 'places_parameters')]
    #[Route('/{place<\d+>}/parameters/trainings', name: 'places_parameters_trainings')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_TRAINING->value.'|'.AclPrivilegesEnum::READ->value, 'place')]
    public function parametersTrainings(Places $place): Response
    {

        if(empty($place))
        return $this->redirectToRoute('home');

        return $this->render('places/parameters.html.twig', [
            'place' => $place,
            'menuPlaces' => 'active',
            'currentTab' => 'trainings' 
        ]);
    }
    #[Route('/{place<\d+>}/parameters/classrooms', name: 'places_parameters_classrooms')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_CLASSROOM->value.'|'.AclPrivilegesEnum::READ->value, 'place')]
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

    #[Route('/{place<\d+>}/parameters/cursuses', name: 'places_parameters_cursuses')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_CURSUS->value.'|'.AclPrivilegesEnum::READ->value, 'place')]
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

    #[Route('/{place<\d+>}/parameters/people', name: 'places_parameters_people')]
    #[IsGranted(AclRessourcesEnum::PLACE_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::READ->value, 'place')]
    public function parametersPeople(Places $place): Response
    {

        if(empty($place))
        return $this->redirectToRoute('home');

        return $this->render('places/parameters.html.twig', [
            'rolesEnum' => UsersRolesPlacesEnum::array(),
            'place' => $place,
            'menuPlaces' => 'active',
            'currentTab' => 'people' 
        ]);
    }
}
