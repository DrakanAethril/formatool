<?php

namespace App\Controller;

use App\Entity\LessonSessions;
use App\Entity\TimeSlots;
use App\Entity\TimeTableGenerator;
use App\Entity\TopicsGroups;
use App\Entity\TopicsTrainings;
use App\Entity\TopicsTrainingsLabel;
use App\Entity\Trainings;
use App\Form\LessonSessionType;
use App\Form\TimeSlotType;
use App\Form\TopicsGroupType;
use App\Form\TopicsTrainingsType;
use App\Repository\LessonSessionsRepository;
use App\Repository\TimeSlotsRepository;
use App\Repository\TopicsGroupsRepository;
use App\Repository\TopicsTrainingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('/manager')]

class TrainingsController extends AbstractController
{

    #[Route('/training/{id}', name: 'training_detail')]
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

    #[Route('/training/{id<\d+>}/topic/add/{tt<\d+>?0}', name: 'training_add_topic')]
    public function addTopic(#[MapEntity(expr: 'repository.find(id)')] Trainings $training, int $tt, TopicsTrainingsRepository $topicsTrainingsRepository, Request $request, EntityManagerInterface $entityManager): Response
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
        
        
        $form = $this->createForm(TopicsTrainingsType::class, $topicTraining);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($topicTraining);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('training_parameters_topics', ['id' => $training->getId()]);
        }


        return $this->render('trainings/add_topic.html.twig', [
            'training' => $training,
            'topicForm' => $form->createView(),
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/training/topic/remove/{id}', name: 'training_remove_topic')]
    public function removeTopic($id, TopicsTrainingsRepository $topicsTrainingsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $topicsTrainings = $topicsTrainingsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($topicsTrainings)) {
            $idTraining = $topicsTrainings->getTrainings()->getId();
            $entityManager->remove($topicsTrainings);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_topics', ['id' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // TRAININGS GROUPS

    #[Route('/training/{id<\d+>}/topics/group/add/{tt<\d+>?0}', name: 'training_add_topics_group')]
    public function addTopicsGroup(#[MapEntity(expr: 'repository.find(id)')] Trainings $training, int $tt, TopicsGroupsRepository $topicsGroupsRepository, Request $request, EntityManagerInterface $entityManager): Response
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
            return $this->redirectToRoute('training_parameters_topics_groups', ['id' => $training->getId()]);
        }


        return $this->render('trainings/add_topics_group.html.twig', [
            'training' => $training,
            'topicsGroupForm' => $form->createView(),
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/training/topics/group/remove/{id}', name: 'training_remove_topics_group')]
    public function removeTopicsGroup($id, TopicsGroupsRepository $topicsGroupsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $topicsTrainings = $topicsGroupsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($topicsTrainings)) {
            $idTraining = $topicsTrainings->getTraining()->getId();
            $entityManager->remove($topicsTrainings);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_topics_groups', ['id' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    // Lesson Sessions

    #[Route('/training/{id<\d+>}/lessonsession/add/{tt<\d+>?0}', name: 'training_add_lessonsession')]
    public function addLessonSession(#[MapEntity(expr: 'repository.find(id)')] Trainings $training, int $tt, LessonSessionsRepository $lessonSessionsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $create = false;
        if(empty($tt)) {
            $tt = 0;
            $lessonSession = new LessonSessions();
            $lessonSession->setTraining($training);
            if(!empty($request->query->get('start'))) {
                $startDate = \DateTime::createFromFormat('Y-m-d h:i:s',str_replace('T', ' ', $request->query->get('start')));
            }
            if(!empty($request->query->get('end'))) {
                $endDate = \DateTime::createFromFormat('Y-m-d h:i:s',str_replace('T', ' ', $request->query->get('end')));
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
        
        
        $form = $this->createForm(LessonSessionType::class, $lessonSession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($lessonSession);
            $entityManager->flush();
            //redirect on training page
            return $this->redirectToRoute('training_parameters_timetable', ['id' => $training->getId()]);
        }


        return $this->render('trainings/add_lesson_session.html.twig', [
            'training' => $training,
            'lessonSessionForm' => $form->createView(),
            'menuTrainings' => 'active',
            'tt' => $tt
        ]);
    }

    #[Route('/training/lessonsessions/remove/{id}', name: 'training_remove_lessonsession')]
    public function removeLessonSession($id, LessonSessionsRepository $lessonSessionsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $lessonSession = $lessonSessionsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($lessonSession)) {
            $idTraining = $lessonSession->getTraining()->getId();
            $entityManager->remove($lessonSession);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters_timetable', ['id' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }


    // PARAMETERS - DEFAULT TO TIMESLOTS TABS

    #[Route('/training/{id<\d+>}/parameters', name: 'training_parameters')]
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

    #[Route('/training/{id<\d+>}/parameters/topics', name: 'training_parameters_topics')]
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

    #[Route('/training/{id<\d+>}/parameters/topics/groups', name: 'training_parameters_topics_groups')]
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

    #[Route('/training/{id<\d+>}/parameters/timetable', name: 'training_parameters_timetable')]
    public function parametersTimetable(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'timetable' 
        ]);
    }

    // PLANNING DISPLAY PAGES

    #[Route('/training/{id<\d+>}/planning', name: 'training_planning')]
    public function planning(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/planning.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/training/{id<\d+>}/timetable/weekly', name: 'training_timetable_weekly')]
    public function timetableWeekly(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/timetable_weekly.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active'
        ]);
    }

    // TIMESLOTS

    #[Route('/training/{id<\d+>}/timeslot/add/{tt<\d+>?0}', name: 'training_add_timeslot')]
    public function addTimeSlot(#[MapEntity(expr: 'repository.find(id)')] Trainings $training, int $tt, TimeSlotsRepository $timeSlotsRepository, Request $request, EntityManagerInterface $entityManager): Response
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
            return $this->redirectToRoute('training_parameters', ['id' => $training->getId()]);
        }


        return $this->render('trainings/add_timeslot.html.twig', [
            'training' => $training,
            'timeSlotForm' => $form->createView(),
            'menuTrainings' => 'active'
        ]);
    }

    #[Route('/training/timeslot/remove/{id}', name: 'training_remove_timeslot')]
    public function removeTimeSlot($id, TimeSlotsRepository $timeSlotsRepository, EntityManagerInterface $entityManager) : Response
    {   
        $timeSlot = $timeSlotsRepository->findOneBy(['id' => intval($id)]);
        if(!empty($timeSlot)) {
            $idTraining = $timeSlot->getTraining()->getId();
            $entityManager->remove($timeSlot);
            $entityManager->flush();
            return $this->redirectToRoute('training_parameters', ['id' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    //TEACHERS
    #[Route('/training/{id<\d+>}/teachers', name: 'training_teachers')]
    public function teachers(Trainings $training): Response
    {
        if(empty($training))
        return $this->redirectToRoute('home');

        $teachers = [];
        foreach($training->getTrainings() as $topic) {
            if(!empty($topic->getTeacher())) {
                $teachers[$topic->getTeacher()->getId()] = $topic->getTeacher();
            }
        }
        return $this->render('teachers/index.html.twig', [
            'teachers' => $teachers,
            'menuTrainings' => 'active'
        ]);

        
    }

    // TIMETABLE MANAGEMENT

    #[Route('/training/{id<\d+>}/timetable/generation', name: 'training_timetable_generation')]
    public function timetable_generate(Trainings $training, EntityManagerInterface $entityManager): Response
    {
        if(empty($training))
        return $this->redirectToRoute('home');
        
        $timeTableGenerator = new TimeTableGenerator($training, $entityManager);
        $timeTableGenerator->generateTimeTable();

        return $this->redirectToRoute('training_parameters_timetable', ['id' => $training->getId()]);
    }
}
