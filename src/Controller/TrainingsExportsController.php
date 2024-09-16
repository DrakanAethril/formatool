<?php

namespace App\Controller;

use App\Config\AclPrivilegesEnum;
use App\Config\AclRessourcesEnum;
use App\Entity\Trainings;
use App\Form\ExportInvoicingType;
use App\Form\SignaturePdfType;
use App\Repository\LessonSessionsRepository;
use App\Repository\UsersTrainingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('app/trainings')]

class TrainingsExportsController extends AbstractController
{
    // EXPORTS - DEFAULT TO SIGNATURE TABS

    #[Route('/{training<\d+>}/exports', name: 'training_exports_routing')]
    #[IsGranted(AclRessourcesEnum::TRAINING_EXPORTS->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function exportsRouting(Trainings $training) {
        if($this->isGranted(AclRessourcesEnum::TRAINING_EXPORTS_SIGNATURE->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_exports_signature', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_EXPORTS_INVOICING->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_exports_invoicing', ['training' => $training->getId()]);
        
        return $this->redirectToRoute('home');
    }

    #[Route('/{training<\d+>}/exports/signature', name: 'training_exports_signature')]
    #[IsGranted(AclRessourcesEnum::TRAINING_EXPORTS_SIGNATURE->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function signature(Trainings $training, Request $request, LessonSessionsRepository $lessonSessionsRepository, UsersTrainingsRepository $usersTrainingsRepository) {
        
        if(empty($training))
        return $this->redirectToRoute('home');

        $dataSignatures = [];
        $studentsList = [];

        $form = $this->createForm(SignaturePdfType::class, null,  []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lessons = $lessonSessionsRepository->findSessionsBetweenDatesForTraining($training, $form->get('start_day')->getData(),$form->get('end_day')->getData());
            if(!empty($lessons)) {
                foreach($lessons as $lesson) {
                    $currentLineData = [
                        'startHour' => $lesson->getStartHour()->format('H:i'),
                        'endHour' => $lesson->getEndHour()->format('H:i'),
                        'teacher' => empty($lesson->getTeacher()) ? 'NA' : $lesson->getTeacher()->getPoliteDisplayName(),
                        'topic' => $lesson->getDisplayName(),
                        'resource' => $lesson->getTopic()->getTopics()->getName()
                    ];
                    if(!empty($lesson->getTrainingOptions())) {
                        foreach($lesson->getTrainingOptions() as $option) {
                            $dataSignatures[$option->getShortName()][$lesson->getDay()->format('d/m/Y')][] = $currentLineData;
                        }
                    } else {
                        $dataSignatures['ALL'][$lesson->getDay()->format('d/m/Y')][] = $currentLineData;
                    }
                }
                $aStudentList = $usersTrainingsRepository->getAllowedStudentsForTraining($training);
                if(!empty($aStudentList)) {
                    foreach($aStudentList as $student) {
                        if(empty($training->getTrainingsOptions())) {
                            $studentsList['ALL'] = $student;
                        } else {
                            if(!empty($student->getTrainingOptions())) {
                                foreach($student->getTrainingOptions() as $option) {
                                    $studentsList[$option->getShortName()][] = $student;
                                }
                            }
                        }
                    } 
                }
            }
        }

        return $this->render('trainings_exports/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'signature',
            'signatureForm' => $form->createView(),
            'signatureData' => $dataSignatures,
            'studentsList' => $studentsList
        ]);
    }

    #[Route('/{training<\d+>}/exports/invoicing', name: 'training_exports_invoicing')]
    #[IsGranted(AclRessourcesEnum::TRAINING_EXPORTS_INVOICING->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function invoicing(Trainings $training, Request $request, LessonSessionsRepository $lessonSessionsRepository) {

        if(empty($training))
        return $this->redirectToRoute('home');

        $dataInvoicing = [];
        $dataInvoicing['NO_TEACHER']['volume'] = 0;
        $dataInvoicing['NO_TEACHER']['detail'] = [];
        $dataInvoicing['NO_TEACHER']['first_name'] = 'NA';
        $dataInvoicing['NO_TEACHER']['last_name'] = 'NA';

        $form = $this->createForm(ExportInvoicingType::class, null,  []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lessons = $lessonSessionsRepository->findSessionsBetweenDatesForTraining($training, $form->get('start_day')->getData(),$form->get('end_day')->getData());
            if(!empty($lessons)) {
                foreach($lessons as $lesson) {
                    if(empty($lesson->getTeacher())) {
                        $teacher = 'NO_TEACHER';
                    } else {
                        $teacher = 'teacher_'.$lesson->getTeacher()->getId();
                    }
                    if(empty($dataInvoicing[$teacher])) {
                        $dataInvoicing[$teacher]['volume'] = 0;
                        $dataInvoicing[$teacher]['detail'] = [];
                        $dataInvoicing[$teacher]['id'] = $lesson->getTeacher()->getId();
                        $dataInvoicing[$teacher]['first_name'] = $lesson->getTeacher()->getPoliteDisplayName();
                        $dataInvoicing[$teacher]['last_name'] = $lesson->getTeacher()->getPoliteDisplayName();
                    }
                    $dataInvoicing[$teacher]['volume'] += $lesson->getLength();
                    $dataInvoicing[$teacher]['detail'][] = $lesson->getDay()->format('d/m/Y').' - '.$lesson->getDisplayName().' - '.$lesson->getLength().'H';
                }
            }
        }
        
        return $this->render('trainings_exports/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'invoicingForm' => $form->createView(),
            'invoicingData' => $dataInvoicing,
            'currentTab' => 'invoicing' 
        ]);
    }
}
