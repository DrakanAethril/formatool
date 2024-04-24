<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\LessonSessionsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app')]
class TeacherAjaxController extends AbstractController
{
    #[Route('/ajax/teacher/{id<\d+>}/timetable/sessions', name: 'ajax_teacher_timetable_sessions')]
    public function timetableSessions(Users $teacher, LessonSessionsRepository $lessonSessionsRepository): Response
    {
        $sessions = [];
        if(empty($teacher))
            $teacher = $this->getUser();
        
        $sessionsDb = $lessonSessionsRepository->findBy(['teacher' => $teacher]);
        foreach($sessionsDb as $sessionDb) {
            $dateStartTime = DateTime::createFromFormat('Y-m-d H:i:s', $sessionDb->getDay()->format('Y-m-d').' '.$sessionDb->getStartHour()->format('H:i:s'));
            $dateEndTime = DateTime::createFromFormat('Y-m-d H:i:s', $sessionDb->getDay()->format('Y-m-d').' '.$sessionDb->getEndHour()->format('H:i:s'));

            $sessions[] = [
                'id' => $sessionDb->getId(),
                'title'=> $sessionDb->getTopic()->getTopics()->getName().' ('.$sessionDb->getTraining()->getTitle().')',
                'start'=> $dateStartTime,
                'end' => $dateEndTime,
                //'backgroundColor' => $timeSlot->getTimeSlotsTypes()->getColor(),
                'allDay' => false,
                //'url' => $this->generateUrl('training_add_lessonsession', ['id' => $sessionDb->getTraining()->getId(), 'tt' => $sessionDb->getId()]),
            ];
        }
        return $this->json(
            $sessions,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
