<?php

namespace App\Controller;

use App\Config\AclPrivilegesEnum;
use App\Config\AclRessourcesEnum;
use App\Config\FinancialItemsTypeEnum;
use App\Config\FinancialItemsSourceEnum;
use App\Config\UsersRolesTrainingsEnum;
use App\Config\UsersStatusTrainingsEnum;
use App\Entity\LessonSessions;
use App\Entity\TimeSlots;
use App\Entity\TimeTableGenerator;
use App\Entity\TopicsGroups;
use App\Entity\TopicsTrainings;
use App\Entity\TrainingFinancialItems;
use App\Entity\Trainings;
use App\Entity\TrainingsOptions;
use App\Entity\UsersTrainings;
use App\Form\FinancialItemType;
use App\Form\LessonSessionType;
use App\Form\TimeSlotType;
use App\Form\TopicsGroupType;
use App\Form\TopicsTrainingsType;
use App\Form\TrainingsOptionsType;
use App\Form\UsersTrainingsType;
use App\Repository\LessonSessionsRepository;
use App\Repository\TimeSlotsRepository;
use App\Repository\TopicsGroupsRepository;
use App\Repository\TopicsTrainingsRepository;
use App\Repository\TrainingFinancialItemsRepository;
use App\Repository\TrainingsOptionsRepository;
use App\Repository\UsersTrainingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('app/trainings')]

class TrainingsController extends AbstractController
{

    #[Route('/{training}/timetable', name: 'training_timetable')]
    #[Route('/{training<\d+>}/timetable/{options}', name: 'training_timetable_with_options')] 
    function timetable(Trainings $training, Request $request, $options=null): Response {
        
        if(empty($training))
        return $this->redirectToRoute('home');
        if(empty($options)) $options = false;

        $focus = date("Y-m-d");
        if(!empty($request->get('focus'))) {
            $dateFocus = \DateTime::createFromFormat('Y-m-d', $request->get('focus'));
            if(!empty($dateFocus)){
                $focus = $dateFocus->format('Y-m-d');
            }
        } else {
            $now = new \DateTime();
            if(!empty($training->getStartTrainingDate()) && $training->getStartTrainingDate() > $now) {
                $focus = $training->getStartTrainingDate()->format('Y-m-d');
            } else {
                $focus = $now->format('Y-m-d');
            }
        }

        return $this->render('trainings/timetable_weekly.html.twig', [
            'training' => $training,
            'focus' => $focus,
            'menuTrainings' => 'active',
            'options' => $options
        ]);
    }

    #[Route('/{training}/syllabus', name: 'training_detail')]
    public function detail(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');
        $volTotal = 0;

        $volCm = 0;
        $volTd = 0;
        $volTp = 0;
        //$nbStudents = 0;

        if(!empty($training->getTrainings())) {
            foreach($training->getTrainings() as $topics) {
                $volCm += $topics->getCm();
                $volTd += $topics->getTd();
                $volTp += $topics->getTp();
                $volTotal += $topics->getTotalVolume();
            }
        }
        

        return $this->render('trainings/detail.html.twig', [
            'training' => $training,
            'volCm' => $volCm,
            'volTd' => $volTd,
            'volTp' => $volTp,
            'volTotal' => $volTotal,
            'menuTrainings' => 'active'
        ]);
    }

    // TOPICS

    #[Route('/{training<\d+>}/topic/add/{tt<\d+>?0}', name: 'training_add_topic')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    public function addTopic(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, TopicsTrainingsRepository $topicsTrainingsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $topicTraining = new TopicsTrainings();
            $topicTraining->setTrainings($training);
            $create = true;
        } else {
            $topicTraining = $topicsTrainingsRepository->findOneBy(['id'=> $tt]);
            /*$oldTopicTrainingLabels = new TopicsTrainings();
            if(!empty($topicTraining->getTopicsTrainingsLabels())) {
                foreach($topicTraining->getTopicsTrainingsLabels() as $label) {
                    $oldTopicTrainingLabels->addTopicsTrainingsLabel($label);
                }
            }
            */
            if(empty($topicTraining) || $topicTraining->getTrainings()->getId() !== $training->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(TopicsTrainingsType::class, $topicTraining, ['training' => $training]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($topicTraining);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('training_parameters_topics', ['training' => $training->getId()]);
        }


        return $this->render('trainings/add_topic.html.twig', [
            'training' => $training,
            'topicForm' => $form->createView(),
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/{training<\d+>}/topic/remove/{id}', name: 'training_remove_topic')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC->value.'|'.AclPrivilegesEnum::DELETE->value, 'training')]
    public function removeTopic(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, TopicsTrainingsRepository $topicsTrainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $topicsTrainings = $topicsTrainingsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($topicsTrainings) && $topicsTrainings->getTrainings()->getId() == $training->getId()) {
            $idTraining = $topicsTrainings->getTrainings()->getId();
            $entityManager->remove($topicsTrainings);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_topics', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // TRAININGS GROUPS

    #[Route('/{training<\d+>}/topics/group/add/{tt<\d+>?0}', name: 'training_add_topics_group')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC_GROUP->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    public function addTopicsGroup(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, TopicsGroupsRepository $topicsGroupsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $topicsGroup = new TopicsGroups();
            $topicsGroup->setTraining($training);
            $create = true;
        } else {
            $topicsGroup = $topicsGroupsRepository->findOneBy(['id'=> $tt]);
            if(empty($topicsGroup) || $topicsGroup->getTraining()->getId() !== $training->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(TopicsGroupType::class, $topicsGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($topicsGroup);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('training_parameters_topics_groups', ['training' => $training->getId()]);
        }


        return $this->render('trainings/add_topics_group.html.twig', [
            'training' => $training,
            'topicsGroupForm' => $form->createView(),
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/{training<\d+>}/topics/group/remove/{id}', name: 'training_remove_topics_group')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC_GROUP->value.'|'.AclPrivilegesEnum::DELETE->value, 'training')]
    public function removeTopicsGroup(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, TopicsGroupsRepository $topicsGroupsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $topicsTrainings = $topicsGroupsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($topicsTrainings) && $topicsTrainings->getTraining()->getId() == $training->getId()) {
            $idTraining = $topicsTrainings->getTraining()->getId();
            $entityManager->remove($topicsTrainings);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_topics_groups', ['id' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // Lesson Sessions

    #[Route('/{training<\d+>}/lessonsession/add/{tt<\d+>?0}', name: 'training_add_lessonsession')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_LESSON_SESSION->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    public function addLessonSession(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, LessonSessionsRepository $lessonSessionsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $tt = 0;
            $lessonSession = new LessonSessions();
            $lessonSession->setTraining($training);
            if(!empty($training->getDefaultClassRoom())) $lessonSession->setClassRooms($training->getDefaultClassRoom());
            if(!empty($request->query->get('start'))) {
                $startDate = \DateTime::createFromFormat('Y-m-d H:i:s',str_replace('T', ' ', $request->query->get('start')));
            }
            if(!empty($request->query->get('end'))) {
                $endDate = \DateTime::createFromFormat('Y-m-d H:i:s',str_replace('T', ' ', $request->query->get('end')));
            }
            if(!empty($startDate)) {
                $lessonSession->setDay($startDate);
                $lessonSession->setStartHour($startDate);
                if(!empty($endDate)) {
                    $lessonSession->setEndHour($endDate);
                    $lessonSession->setLength(ceil( ($endDate->format('U')-$startDate->format('U')) / 3600 ));
                }    
            }
            
            $create = true;
        } else {
            $lessonSession = $lessonSessionsRepository->findOneBy(['id'=> $tt]);
            if(empty($lessonSession) || $lessonSession->getTraining()->getId() !== $training->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(LessonSessionType::class, $lessonSession,[
            'training' => $training
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($lessonSession);
            $entityManager->flush();
            //redirect on training page
            $redirect = $this->generateUrl('training_parameters_timetable', ['training' => $training->getId()]).'?focus='.$lessonSession->getDay()->format('Y-m-d');
            return $this->redirect($redirect);
        }


        return $this->render('trainings/add_lesson_session.html.twig', [
            'training' => $training,
            'lessonSessionForm' => $form->createView(),
            'menuTrainings' => 'active',
            'tt' => $tt
        ]);
    }

    #[Route('/{training<\d+>}/lessonsessions/remove/{id}', name: 'training_remove_lessonsession')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_LESSON_SESSION->value.'|'.AclPrivilegesEnum::DELETE->value, 'training')]
    public function removeLessonSession(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, LessonSessionsRepository $lessonSessionsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $lessonSession = $lessonSessionsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($lessonSession) && $lessonSession->getTraining()->getId() == $training->getId()) {
            $idTraining = $lessonSession->getTraining()->getId();
            $entityManager->remove($lessonSession);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_timetable', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // TIMESLOTS

    #[Route('/{training<\d+>}/timeslot/add/{tt<\d+>?0}', name: 'training_add_timeslot')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TIMESLOT->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    public function addTimeSlot(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, TimeSlotsRepository $timeSlotsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $timeSlot = new TimeSlots();
            $timeSlot->setTraining($training);
            $create = true;
        } else {
            $timeSlot = $timeSlotsRepository->findOneBy(['id'=> $tt]);
            if(empty($timeSlot) || $timeSlot->getTraining()->getId() !== $training->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(TimeSlotType::class, $timeSlot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeSlot);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('training_parameters', ['training' => $training->getId()]);
        }


        return $this->render('trainings/add_timeslot.html.twig', [
            'training' => $training,
            'timeSlotForm' => $form->createView(),
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/{training<\d+>}/timeslot/remove/{id}', name: 'training_remove_timeslot')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TIMESLOT->value.'|'.AclPrivilegesEnum::DELETE->value, 'training')]
    public function removeTimeSlot(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, TimeSlotsRepository $timeSlotsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $timeSlot = $timeSlotsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($timeSlot) && $timeSlot->getTraining()->getId() == $training->getId()) {
            $idTraining = $timeSlot->getTraining()->getId();
            $entityManager->remove($timeSlot);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // Financial entries
    #[Route('/{training<\d+>}/financial/add_per_session/{tt<\d+>?0}', name: 'training_add_financial_session')]
    #[Route('/{training<\d+>}/financial/add_per_student/{tt<\d+>?0}', name: 'training_edit_financial_student')]
    #[Route('/{training<\d+>}/financial/add_manual_item/{tt<\d+>?0}', name: 'training_edit_financial_manual')]
    #[Route('/{training<\d+>}/financial/edit/{tt<\d+>?0}', name: 'training_edit_financial_item')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_FINANCIAL->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    public function addFinancialItem(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, TrainingFinancialItemsRepository $financialItemsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $create = false;
        if(empty($tt)) {
            $routeName = $request->attributes->get('_route');
            $tt = 0;
            $financialItem = new TrainingFinancialItems();
            $financialItem->setTraining($training);
           
            switch($routeName) {
                case 'training_add_financial_session':
                    $financialItem->setSource(FinancialItemsSourceEnum::SourceLesson->value);
                    break;
                case 'training_edit_financial_student':
                    $financialItem->setSource(FinancialItemsSourceEnum::SourceStudent->value);
                    break;
                case 'training_edit_financial_manual':
                    $financialItem->setSource(FinancialItemsSourceEnum::SourceManual->value);
                    break;
                default:
                    return $this->redirectToRoute('home');
            }
            $create = true;
        } else {
            $financialItem = $financialItemsRepository->findOneBy(['id'=> $tt]);
            if(empty($financialItem) || $financialItem->getTraining()->getId() !== $training->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        $source = FinancialItemsSourceEnum::from($financialItem->getSource());
        
        
        $form = $this->createForm(FinancialItemType::class, $financialItem,  [
            'source' => $source,
            'typeEntry' => $financialItem->getType() ? FinancialItemsTypeEnum::from($financialItem->getType()) : null,
            'sourceEntry' => $financialItem->getSource() ? FinancialItemsSourceEnum::from($financialItem->getSource()) : null,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $financialItem->setType($form->get('typeEntry')->getData()->value);
            $entityManager->persist($financialItem);
            $entityManager->flush();
            //redirect on training page
            $redirect = $this->generateUrl('training_parameters_financial', ['training' => $training->getId()]);
            return $this->redirect($redirect);
        }


        return $this->render('trainings/add_financial_item.html.twig', [
            'training' => $training,
            'financialItemForm' => $form->createView(),
            'menuTrainings' => 'active',
            'tt' => $tt,
            'isSession' => $source == FinancialItemsSourceEnum::SourceLesson,
            'isStudent' => $source == FinancialItemsSourceEnum::SourceStudent,
            'isManual' => $source == FinancialItemsSourceEnum::SourceManual,
        ]);
    }
    
    #[Route('/{training<\d+>}/financial/remove/{id}', name: 'training_remove_financial_item')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_FINANCIAL->value.'|'.AclPrivilegesEnum::DELETE->value, 'training')]
    public function removeFinancialItem(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, TrainingFinancialItemsRepository $trainingFinancialItemsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $financialItem = $trainingFinancialItemsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($financialItem) && $financialItem->getTraining()->getId() == $training->getId()) {
            $idTraining = $financialItem->getTraining()->getId();
            $entityManager->remove($financialItem);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_financial', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // USERS
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    #[Route('/{training<\d+>}/user/add/{tt<\d+>?0}', name: 'training_add_person')]
    public function addPerson(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, UsersTrainingsRepository $usersTrainingsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $userTrainings = new UsersTrainings();
            $userTrainings->setTraining($training);
            $create = true;
            $roles = [];
            $status = null;
        } else {
            $userTrainings = $usersTrainingsRepository->findOneBy(['id'=> $tt]);
            $status = UsersStatusTrainingsEnum::from($userTrainings->getStatus());
            $roles = [];
            if(!empty($userTrainings->getRoles())) {
                foreach($userTrainings->getRoles() as $role) {
                    $roles[] = UsersRolesTrainingsEnum::from($role);
                }
            }
            
            if(empty($userTrainings)) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(UsersTrainingsType::class, $userTrainings, [
            'status' => $status,
            'roles' => $roles,
            'create' => $create,
            'training' => $training
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userTrainings->setStatus($form->get('statusEntry')->getData()->value);
            $newRoles = [];
            foreach($form->get('rolesEntry')->getData() as $role) {
                $newRoles[] = $role->value;
            }
            $userTrainings->setRoles($newRoles);
            $entityManager->persist($userTrainings);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('trainings_parameters_people', ['training' => $training->getId()]);
        }


        return $this->render('trainings/add_people.html.twig', [
            'training' => $training,
            'userForm' => $form->createView(),
            'menuPlaces' => 'active'
        ]);
    }

    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::DELETE->value, 'training')]
    #[Route('/{training<\d+>}/user/remove/{id}', name: 'training_remove_person')]
    public function removePerson(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, UsersTrainingsRepository $usersTrainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $userTrainings = $usersTrainingsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($userTrainings)) {
            $idTraining = $training->getId();
            if($userTrainings->getTraining()->getId() != $training->getId()) {
                return $this->redirectToRoute('home');
            }
            $userTrainings->setStatus(UsersStatusTrainingsEnum::INACTIVE->value);
            $entityManager->persist($userTrainings);
            $entityManager->flush();
            return $this->redirectToRoute('trainings_parameters_people', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{training<\d+>}/user/delete/{id}', name: 'training_delete_person')]
    public function deletePerson(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, UsersTrainingsRepository $usersTrainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $userTrainings = $usersTrainingsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($userTrainings)) {
            $idTraining = $training->getId();
            if($userTrainings->getTraining()->getId() != $training->getId()) {
                return $this->redirectToRoute('home');
            }
            $entityManager->remove($userTrainings);
            $entityManager->flush();
            return $this->redirectToRoute('trainings_parameters_people', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
    
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    #[Route('/{training<\d+>}/user/reactivate/{id}', name: 'training_reactivate_person')]
    public function reactivatePerson(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, UsersTrainingsRepository $usersTrainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $userTrainings = $usersTrainingsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($userTrainings)) {
            $idTraining = $training->getId();
            if($userTrainings->getTraining()->getId() != $training->getId()) {
                return $this->redirectToRoute('home');
            }
            $userTrainings->setStatus(UsersStatusTrainingsEnum::ACTIVE->value);
            $entityManager->persist($userTrainings);
            $entityManager->flush();
            return $this->redirectToRoute('trainings_parameters_people', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // OPTIONS

    #[Route('/{training<\d+>}/options/add/{tt<\d+>?0}', name: 'training_add_option')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_OPTION->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    public function addOption(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, TrainingsOptionsRepository $trainingsOptionsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $option = new TrainingsOptions();
            $option->setTraining($training);
            $create = true;
        } else {
            $option = $trainingsOptionsRepository->findOneBy(['id'=> $tt]);
            if(empty($option) || $option->getTraining()->getId() !== $training->getId()) {
                return $this->redirectToRoute('home');
            }
        }
        
        
        $form = $this->createForm(TrainingsOptionsType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($option);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('training_parameters_options', ['training' => $training->getId()]);
        }


        return $this->render('trainings/add_option.html.twig', [
            'training' => $training,
            'optionsForm' => $form->createView(),
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/{training<\d+>}/options/remove/{id}', name: 'training_remove_option')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_OPTION->value.'|'.AclPrivilegesEnum::DELETE->value, 'training')]
    public function removeOption(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, $id, TrainingsOptionsRepository $trainingsOptionsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $option = $trainingsOptionsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($option) && $option->getTraining()->getId() == $training->getId()) {
            $idTraining = $option->getTraining()->getId();
            $entityManager->remove($option);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_options', ['training' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // PARAMETERS - DEFAULT TO TIMESLOTS TABS

    #[Route('/{training<\d+>}/parameters', name: 'training_parameters')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parameterRouting(Trainings $training) {
        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TIMESLOT->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_timeslots', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC_GROUP->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_topics_groups', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_topics', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_LESSON_SESSION->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_timetable', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_FINANCIAL->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_financial', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_PARAMETERS_OPTION->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_parameters_options', ['training' => $training->getId()]);
        
            return $this->redirectToRoute('home');
    }

    #[Route('/{training<\d+>}/parameters/options', name: 'training_parameters_options')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_OPTION->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parametersOptions(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'options' 
        ]);
    }

    #[Route('/{training<\d+>}/parameters/timeslots', name: 'training_parameters_timeslots')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TIMESLOT->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parameterstimeSlots(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'timeSlots' 
        ]);
    }

    #[Route('/{training<\d+>}/parameters/topics', name: 'training_parameters_topics')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parametersTopics(Trainings $training, LessonSessionsRepository $lessonSessionsRepository): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        // Get Volumes
        $volumesByTopics = $lessonSessionsRepository->getAssignedVolumesByTopic($training);


        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'volumesByTopics' => $volumesByTopics,
            'menuTrainings' => 'active',
            'currentTab' => 'topics' 
        ]);
    }

    #[Route('/{training<\d+>}/parameters/topics/groups', name: 'training_parameters_topics_groups')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_TOPIC_GROUP->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parametersTopicsGroups(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'topicsGroups' 
        ]);
    }

    #[Route('/{training<\d+>}/parameters/timetable', name: 'training_parameters_timetable')]
    #[Route('/{training<\d+>}/parameters/timetable/{options}', name: 'training_parameters_timetable_with_options')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_LESSON_SESSION->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parametersTimetable(Trainings $training, Request $request, $options=null): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        if(empty($options)) $options = false;
        

        $focus = date("Y-m-d");
        if(!empty($request->get('focus'))) {
            $dateFocus = \DateTime::createFromFormat('Y-m-d', $request->get('focus'));
            if(!empty($dateFocus)){
                $focus = $dateFocus->format('Y-m-d');
            }
        } else {
            $now = new \DateTime();
            if(!empty($training->getStartTrainingDate()) && $training->getStartTrainingDate() > $now) {
                $focus = $training->getStartTrainingDate()->format('Y-m-d');
            } else {
                $focus = $now->format('Y-m-d');
            }
        }

        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'timetable',
            'focus' => $focus,
            'options' => $options 
        ]);
    }

    #[Route('/{training<\d+>}/parameters/financial', name: 'training_parameters_financial')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_FINANCIAL->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parametersFinancial(Trainings $training, Request $request, LessonSessionsRepository $lessonSessionsRepository): Response
    {

       // if(empty($training) || empty($training->isActivateFinancialManagement()) )
       // return $this->redirectToRoute('home');

        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'financial',
            'volumePerLessonType' => $lessonSessionsRepository->getTotalLengthPerTypeForTraining($training),
            'volumePerStudent' => 8
        ]);
    }

    #[Route('/{training<\d+>}/parameters/people', name: 'trainings_parameters_people')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_USER->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function parametersPeople(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/parameters.html.twig', [
            'rolesEnum' => UsersRolesTrainingsEnum::array(),
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'people' 
        ]);
    }

    // PLANNING DISPLAY PAGES

    #[Route('/{training<\d+>}/planning', name: 'training_planning')]
    public function planning(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/planning.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active'
        ]);
    }

    //TEACHERS
    #[Route('/{training<\d+>}/teachers', name: 'training_teachers')]
    public function teachers(Trainings $training): Response
    {
        if(empty($training))
        return $this->redirectToRoute('home');

        $teachers = [];
        foreach($training->getUsersTrainings() as $userTraining) {
            //dd($userTraining); 
            if(in_array(UsersRolesTrainingsEnum::TEACHER->value, $userTraining->getRoles()) ) {
                $teachers[$userTraining->getId()] = $userTraining;
            }
        }
        return $this->render('teachers/index.html.twig', [
            'teachers' => $teachers,
            'training' => $training,
            'menuTrainings' => 'active'
        ]);

        
    }

    //STUDENTS
    #[Route('/{training<\d+>}/students', name: 'training_students')]
    public function students(Trainings $training): Response
    {
        if(empty($training))
        return $this->redirectToRoute('home');

        $students = [];
        foreach($training->getUsersTrainings() as $userTraining) {
            //dd($userTraining); 
            if(in_array(UsersRolesTrainingsEnum::STUDENT->value, $userTraining->getRoles()) ) {
                $students[$userTraining->getId()] = $userTraining;
            }
        }
        
        return $this->render('students/index.html.twig', [
            'students' => $students,
            'training' => $training,
            'menuTrainings' => 'active'
        ]);

        
    }

    // TIMETABLE MANAGEMENT

    #[Route('/{training<\d+>}/timetable/generation', name: 'training_timetable_generation')]
    #[IsGranted(AclRessourcesEnum::TRAINING_PARAMETERS_LESSON_SESSION->value.'|'.AclPrivilegesEnum::WRITE->value, 'training')]
    public function timetable_generate(Trainings $training, EntityManagerInterface $entityManager): Response
    {
        if(empty($training))
        return $this->redirectToRoute('home');
        
        $timeTableGenerator = new TimeTableGenerator($training, $entityManager);
        $timeTableGenerator->generateTimeTable();

        return $this->redirectToRoute('training_parameters_timetable', ['id' => $training->getId()]);
    }
}
