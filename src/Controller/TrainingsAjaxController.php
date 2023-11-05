<?php

namespace App\Controller;

use App\Entity\Trainings;
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
}