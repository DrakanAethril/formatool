<?php

namespace App\Controller;

use App\Entity\TimeSlots;
use App\Entity\TopicsTrainings;
use App\Entity\TopicsTrainingsLabel;
use App\Entity\Trainings;
use App\Form\TimeSlotType;
use App\Form\TopicsTrainingsType;
use App\Repository\TimeSlotsRepository;
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

        $volGen = 0;
        $volProjet = 0;
        $volTech = 0;
        $nbStudents = 0;

        if(!empty($training->getTrainings())) {
            foreach($training->getTrainings() as $topics) {
                if(!empty($topics->getTopicsTrainingsLabels())) {
                    foreach($topics->getTopicsTrainingsLabels() as $label) {
                        switch($label->getId()) {
                            case TopicsTrainingsLabel::GENERAL:
                                $volGen += $topics->getTotalVolume();
                            break;
                            case TopicsTrainingsLabel::TECHNIQUE:
                                $volTech += $topics->getTotalVolume();
                            break;
                            case TopicsTrainingsLabel::PROJET:
                                $volProjet += $topics->getTotalVolume();
                            break;
                            default:
                        }
                    }
                }
            }
        }
        

        return $this->render('trainings/detail.html.twig', [
            'training' => $training,
            'volGen' => $volGen,
            'volTech' => $volTech,
            'volProjet' => $volProjet,
            'nbStudents' => $nbStudents,
            'menuTrainings' => 'active'
        ]);
    }

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
            return $this->redirectToRoute('training_detail', ['id' => $training->getId()]);
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
            return $this->redirectToRoute('training_detail', ['id' => $idTraining]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/training/{id<\d+>}/parameters', name: 'training_parameters')]
    public function parameters(Trainings $training): Response
    {

        if(empty($training))
        return $this->redirectToRoute('home');

        return $this->render('trainings/parameters.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active'
        ]);
    }

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
}
