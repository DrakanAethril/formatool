<?php

namespace App\Controller;

use App\Config\AclPrivilegesEnum;
use App\Config\AclRessourcesEnum;
use App\Config\FinancialItemsSourceEnum;
use App\Config\FinancialItemsTypeEnum;
use App\Entity\Trainings;
use App\Repository\LessonSessionsRepository;
use App\Repository\LessonTypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/app/training/{training<\d+>}/reporting')]

class TrainingsReportingController extends AbstractController
{

    #[Route('/', name: 'training_reporting')]
    #[IsGranted(AclRessourcesEnum::TRAINING_REPORTING->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function trainingReportingRouting(Trainings $training) {
        if($this->isGranted(AclRessourcesEnum::TRAINING_REPORTING_FINANCIAL->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_reporting_financial', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_REPORTING_PEDAGOGIC->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_reporting_pedagogic', ['training' => $training->getId()]);

        if($this->isGranted(AclRessourcesEnum::TRAINING_REPORTING_SCHOLARSHIP->value.'|'.AclPrivilegesEnum::READ->value, $training)) 
            return $this->redirectToRoute('training_reporting_scholarship', ['training' => $training->getId()]);

            return $this->redirectToRoute('home');
    }
    
    #[Route('/scholarship', name: 'training_reporting_scholarship')]
    #[IsGranted(AclRessourcesEnum::TRAINING_REPORTING_SCHOLARSHIP->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function scholarship(#[MapEntity(expr: 'repository.find(training)')] Trainings $training): Response
    {
        return $this->render('trainings_reporting/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'scholarship'
        ]);
    }

    #[Route('/pedagogic', name: 'training_reporting_pedagogic')]
    #[IsGranted(AclRessourcesEnum::TRAINING_REPORTING_PEDAGOGIC->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function pedagogic(
        #[MapEntity(expr: 'repository.find(training)')] Trainings $training, 
        LessonSessionsRepository $lessonSessionsRepository, 
        LessonTypesRepository $lessonTypesRepository,
        ChartBuilderInterface $chartBuilder
    ): Response
    {
        $volumePerSessionType = $lessonSessionsRepository->getTotalLengthPerTypeForTraining($training);
        $allTypesNamed = $lessonTypesRepository->getAllTypesIndexedNamed();

        //$chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $labels = [];
        $colors = [];
        $data = [];
        if(!empty($volumePerSessionType)) {
            foreach($volumePerSessionType as $keyVPST => $vVPST) {
                $labels[] = is_string($allTypesNamed[$keyVPST]) ? $allTypesNamed[$keyVPST] : $allTypesNamed[$keyVPST]->getName();
                $colors[] = is_string($allTypesNamed[$keyVPST]) ? '#D3D3D3' : $allTypesNamed[$keyVPST]->getAgendaColor();
                $data[] = $vVPST;
            }
        }

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nombre d\'heures par type de cours',
                    'backgroundColor' => $colors,
                    'data' => $data,
                ],
            ]
        ]);
        $chart->setOptions([
            'responsive'=> true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ]
            ]
        ]);

        
        return $this->render('trainings_reporting/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'pedagogic',
            'volumePerSessionsType' => $volumePerSessionType,
            'totalLessonsVolume' => $lessonSessionsRepository->getGlobalSessionsVolumeForTraining($training, $volumePerSessionType),
            'allTypesNamed' => $allTypesNamed,
            'chart' => $chart
        ]);
    }

    #[Route('/', name: 'training_reporting')]
    #[Route('/financial', name: 'training_reporting_financial')]
    #[IsGranted(AclRessourcesEnum::TRAINING_REPORTING_FINANCIAL->value.'|'.AclPrivilegesEnum::READ->value, 'training')]
    public function financial(#[MapEntity(expr: 'repository.find(training)')] Trainings $training, LessonSessionsRepository $lessonSessionsRepository): Response
    {
        $totalCost = 0;
        $totalGain = 0;
        $volumePerSessionType = $lessonSessionsRepository->getTotalLengthPerTypeForTraining($training);
        $nbStudents = $training->getFinancialEligibleStudents();



        if(!empty($training->getTrainingFinancialItems())) {
            foreach($training->getTrainingFinancialItems() as $financialItem) {
                $totalItemValue = 0;
                switch(FinancialItemsSourceEnum::tryFrom($financialItem->getSource())) {
                    case FinancialItemsSourceEnum::SourceLesson:
                        if(empty($financialItem->getLessonType())) {
                            $totalItemValue = 0*$financialItem->getValue();
                        } else {
                            $totalItemValue = $volumePerSessionType['type_'.$financialItem->getLessonType()->getId()]*$financialItem->getValue();
                        }
                        break;
                    case FinancialItemsSourceEnum::SourceStudent:
                        $totalItemValue = $nbStudents*$financialItem->getValue();
                        break;
                    case FinancialItemsSourceEnum::SourceManual:
                        $totalItemValue = $financialItem->getQuantity()*$financialItem->getValue();
                        break;
                    default:
                        dd($financialItem);
                }
                if(!empty($totalItemValue)) {
                    if(FinancialItemsTypeEnum::tryFrom($financialItem->getType()) == FinancialItemsTypeEnum::TypeGain) {
                        $totalGain += $totalItemValue;
                    } else {
                        $totalCost += $totalItemValue;
                    }
                }
            }
        }

        return $this->render('trainings_reporting/index.html.twig', [
            'training' => $training,
            'menuTrainings' => 'active',
            'currentTab' => 'financial',
            'volumePerLessonType' => $volumePerSessionType,
            'nbStudents' => $nbStudents,
            'totalGain' => $totalGain,
            'totalCost' => $totalCost,
            'totalGlobal' => $totalGain - $totalCost
        ]);
    }
}


