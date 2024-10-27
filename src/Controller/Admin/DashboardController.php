<?php

namespace App\Controller\Admin;

use App\Entity\ClassRooms;
use App\Entity\Cursus;
use App\Entity\CursusType;
use App\Entity\LessonSessions;
use App\Entity\LessonTypes;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Places;
use App\Entity\Skills;
use App\Entity\TimeSlots;
use App\Entity\TimeSlotsTypes;
use App\Entity\Trainings;
use App\Entity\Topics;
use App\Entity\TopicsGroups;
use App\Entity\TopicsTrainings;
use App\Entity\TopicsTrainingsLabel;
use App\Entity\TrainingsModality;
use App\Entity\Users;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(TrainingsCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Formatool');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Website', 'fa fa-home', '');
        yield MenuItem::linkToCrud('Etablissements', 'fas fa-school', Places::class);
        yield MenuItem::linkToCrud('Salles', 'fas fa-person-chalkboard', ClassRooms::class);
        yield MenuItem::linkToCrud('Formations', 'fas fa-certificate', Trainings::class);
        yield MenuItem::linkToCrud('Unités d\'enseignements', 'fas fa-tag', TopicsGroups::class);
        yield MenuItem::linkToCrud('Compétences', 'fas fa-certificate', Skills::class);
        yield MenuItem::linkToCrud('Matières', 'fas fa-chalkboard-user', Topics::class);
        yield MenuItem::linkToCrud('Matières par formation', 'fas fa-chalkboard-user', TopicsTrainings::class);
        yield MenuItem::linkToCrud('Cours', 'fas fa-calendar-xmark', LessonSessions::class);
        yield MenuItem::linkToCrud('Type de cours', 'fas fa-calendar-xmark', LessonTypes::class);
        yield MenuItem::linkToCrud('Labels', 'fas fa-tag', TopicsTrainingsLabel::class);
        yield MenuItem::linkToCrud('Périodes', 'fas fa-calendar', TimeSlots::class);
        yield MenuItem::linkToCrud('Types de périodes', 'fas fa-calendar-xmark', TimeSlotsTypes::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', Users::class);
        yield MenuItem::linkToCrud('Cursus', 'fas fa-certificate', Cursus::class);
        yield MenuItem::linkToCrud('Types de cursus', 'fas fa-certificate', CursusType::class);
        yield MenuItem::linkToCrud('Modalités de formation', 'fas fa-certificate', TrainingsModality::class);
    }
}
