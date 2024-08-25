<?php

namespace App\Controller;

use App\Entity\LessonSessions;
use App\Entity\Trainings;
use App\Entity\TrainingsOptions;
use App\Repository\LessonSessionsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[Route('/app')]
class TrainingsAjaxController extends AbstractController
{
    #[Route('/ajax/training/{id<\d+>}/events', name: 'ajax_training_timeslots')]
    public function timeslots(Trainings $training): Response
    {
        $timeSlots = [];
        if(empty($training))
            return $this->json(
                $timeSlots,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );

        foreach($training->getTimeSlots() as $timeSlot) {
            if(!empty($timeSlot->getInactive())) {
                continue;
            }
            $allDay = false;
            if($timeSlot->getEndDate()->getTimestamp() - $timeSlot->getStartDate()->getTimestamp() > 3600*23) { 
                //if more than 23H let's add one hour to end date to manage exclusive enddates
                $newDate = new DateTime();
                $timeSlot->setEndDate($newDate->setTimestamp($timeSlot->getEndDate()->getTimestamp() + 3600));
                $allDay = true;
            }
            $timeSlots[] = [
                'id' => $timeSlot->getId(),
                'title'=> $timeSlot->getName(),
                'start'=> $timeSlot->getStartDate(),
                'end' => $timeSlot->getEndDate(),
                'backgroundColor' => $timeSlot->getTimeSlotsTypes()->getColor(),
                'allDay' => $allDay
            ];
        }
        return $this->json(
            $timeSlots,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/ajax/training/{id<\d+>}/timetable/sessions', name: 'ajax_training_timetable_sessions')]
    #[Route('/ajax/training/{id<\d+>}/timetable/sessions/{options}', name: 'ajax_training_timetable_sessions_with_options')]
    #[Route('/ajax/training/{id<\d+>}/public/timetable/sessions', name: 'ajax_training_public_timetable_sessions')]
    #[Route('/ajax/training/{id<\d+>}/public/timetable/sessions/{options}', name: 'ajax_training_public_timetable_sessions_with_options')]
    public function timetableSessions(Trainings $training, LessonSessionsRepository $lessonSessionsRepository, Request $request, $options = null): Response
    {
        $sessions = [];
        if(empty($training))
            return $this->json(
                $sessions,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );
        $routeName = $request->attributes->get('_route');
        $sessionsDb = $lessonSessionsRepository->findBy(['training' => $training]);
        foreach($sessionsDb as $sessionDb) {
            $dateStartTime = DateTime::createFromFormat('Y-m-d H:i:s', $sessionDb->getDay()->format('Y-m-d').' '.$sessionDb->getStartHour()->format('H:i:s'));
            $dateEndTime = DateTime::createFromFormat('Y-m-d H:i:s', $sessionDb->getDay()->format('Y-m-d').' '.$sessionDb->getEndHour()->format('H:i:s'));
            $sessionDbOptions = empty($sessionDb->getTrainingOptions()) ? [] : $sessionDb->getTrainingOptions()->toArray();
            if(!empty($options) && $options != 'all' && !in_array($options, $sessionDbOptions)) continue;
            $sessionData = [
                'id' => $sessionDb->getId(),
                'title'=> $sessionDb->getDisplayName(),
                'start'=> $dateStartTime,
                'end' => $dateEndTime,
                'allDay' => false,
                'extendedProps' => [
                    'topic' => $sessionDb->getTopic()->getTopics()->getName(),
                    'training' => $sessionDb->getTraining()->getId(),
                    'lessonType' => empty($sessionDb->getLessonType()) ? 'NA' : $sessionDb->getLessonType()->getName(),
                    'classRoom' => (empty($sessionDb->getClassRooms())) ? 'NA' : $sessionDb->getClassRooms()->getName(),
                    'options' => empty($sessionDbOptions) ? '' : implode('/', $sessionDbOptions).' '
                ]
            ];
            if($routeName == 'ajax_training_timetable_sessions' || $routeName == 'ajax_training_timetable_sessions_with_options') {
                $sessionData['backgroundColor'] = empty($sessionDb->getLessonType()) ? '#D3D3D3' : $sessionDb->getLessonType()->getAgendaColor();
                $sessionData['url'] = $this->generateUrl('training_add_lessonsession', ['training' => $sessionDb->getTraining()->getId(), 'tt' => $sessionDb->getId()]);
                $sessionData['extendedProps']['updateUrl'] = $this->generateUrl('training_update_lessonsession', ['training' => $sessionDb->getTraining()->getId(), 'tt' => $sessionDb->getId()]);
            } else {
                $sessionData['backgroundColor'] = TrainingsOptions::DEFAULT_PUBLIC_AGENDA_COLOR;
                if(!empty($sessionDbOptions) && count($sessionDbOptions) == 1) {
                    $sessionData['backgroundColor'] = $sessionDb->getTrainingOptions()->first()->getAgendaColor();
                }
            }
            $sessions[] = $sessionData;
        }
        return $this->json(
            $sessions,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/training/{training<\d+>}/lessonsession/update/{tt<\d+>?0}', name: 'training_update_lessonsession')]
    public function addLessonSession(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, int $tt, LessonSessionsRepository $lessonSessionsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $lessonSession = $lessonSessionsRepository->findOneBy(['id'=> $tt]);
        if(empty($lessonSession) || $lessonSession->getTraining()->getId() !== $training->getId()) {
            return new JsonResponse([
                'error' => 'not authorized'
            ], 403);
        }
        $start = new DateTime($request->get('startDate'));
        //$startDate = $request->query->get('startDate');
        $end = new DateTime($request->get('endDate'));
        //dd($startDate.' '.$endDate);
        $lessonSession->setDay($start);
        $lessonSession->setStartHour(new DateTime($start->format("H:i:00")));
        $lessonSession->setEndHour(new DateTime($end->format("H:i:00")));
        
        $entityManager->persist($lessonSession);
        $entityManager->flush();
        //redirect on training page
        return new JsonResponse([
            'msg' => 'saved'
        ], 200);
        
    }
}
