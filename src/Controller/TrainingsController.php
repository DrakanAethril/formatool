<?php

namespace App\Controller;

use App\Entity\TopicsTrainings;
use App\Entity\TopicsTrainingsLabel;
use App\Entity\Trainings;
use App\Form\TopicsTrainingsType;
use App\Repository\TopicsTrainingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            'nbStudents' => $nbStudents
        ]);
    }

    #[Route('/training/{id}/topic/add', name: 'training_add_topic')]
    public function addTopic(Trainings $training, Request $request, EntityManagerInterface $entityManager): Response
    {

        $topicTraining = new TopicsTrainings();
        $topicTraining->setTrainings($training);
        $form = $this->createForm(TopicsTrainingsType::class, $topicTraining);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($topicTraining);
            $entityManager->persist($topicTraining);
            $entityManager->flush();

            if(!empty($topicTraining->getTopicsTrainingsLabels())) {
                foreach($topicTraining->getTopicsTrainingsLabels() as $label) {
                    $label->addTopicsTraining($topicTraining);
                    $entityManager->persist($label);
                    $entityManager->flush();
                }
            }

            //redirect on traingin page
            return $this->redirectToRoute('training_detail', ['id' => $training->getId()]);
        }


        return $this->render('trainings/add_topic.html.twig', [
            'training' => $training,
            'topicForm' => $form->createView(),
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
}
