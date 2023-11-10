<?php

namespace App\Controller;

use App\Entity\Trainings;
use App\Repository\LessonSessionsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function timetableSessions(Trainings $training, LessonSessionsRepository $lessonSessionsRepository): Response
    {
        $sessions = [];
        if(empty($training))
            return $this->json(
                $sessions,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );
        $sessionsDb = $lessonSessionsRepository->findBy(['training' => $training]);
        foreach($sessionsDb as $sessionDb) {
            $allDay = false;
            /*if($timeSlot->getEndDate()->getTimestamp() - $timeSlot->getStartDate()->getTimestamp() > 3600*23) { 
                //if more than 23H let's add one hour to end date to manage exclusive enddates
                $newDate = new DateTime();
                $timeSlot->setEndDate($newDate->setTimestamp($timeSlot->getEndDate()->getTimestamp() + 3600));
                $allDay = true;
            }*/
            $dateStartTime = DateTime::createFromFormat('Y-m-d H:i:s', $sessionDb->getDay()->format('Y-m-d').' '.$sessionDb->getStartHour()->format('H:i:s'));
            $dateEndTime = DateTime::createFromFormat('Y-m-d H:i:s', $sessionDb->getDay()->format('Y-m-d').' '.$sessionDb->getEndHour()->format('H:i:s'));

            $sessions[] = [
                'id' => $sessionDb->getId(),
                'title'=> $sessionDb->getTopic()->getTopics()->getName(),
                'start'=> $dateStartTime,
                'end' => $dateEndTime,
                //'backgroundColor' => $timeSlot->getTimeSlotsTypes()->getColor(),
                'allDay' => false
            ];
        }
        return $this->json(
            $sessions,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
