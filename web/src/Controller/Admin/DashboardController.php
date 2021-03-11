<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Entity\Circuit;
use App\Entity\Driver;
use App\Entity\Race;
use App\Entity\RaceCarDriver;
use App\Entity\RaceCarDriverRaceLap;
use App\Entity\RaceCarDriverRacePitStop;
use App\Entity\Season;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserAction;
use App\Entity\UserBlock;
use App\Entity\UserDevice;
use App\Entity\UserExport;
use App\Entity\UserFollower;
use App\Entity\UserNotification;
use App\Entity\UserOauthProvider;
use App\Entity\UserPoint;
use App\Entity\UserTfaMethod;
use App\Entity\UserTfaRecoveryCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('contents/admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('RaceViz Admin')
        ;
    }

    public function configureCrud(): Crud
    {
        return Crud::new();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('App'),
            MenuItem::linkToCrud('Circuits', 'fas fa-folder-open', Circuit::class),
            MenuItem::linkToCrud('Seasons', 'fas fa-folder-open', Season::class),
            MenuItem::linkToCrud('Teams', 'fas fa-folder-open', Team::class),
            MenuItem::linkToCrud('Drivers', 'fas fa-folder-open', Driver::class),
            MenuItem::linkToCrud('Cars', 'fas fa-folder-open', Car::class),
            MenuItem::linkToCrud('Races', 'fas fa-folder-open', Race::class),
            MenuItem::linkToCrud('Race Car Drivers', 'fas fa-folder-open', RaceCarDriver::class),
            MenuItem::linkToCrud('Race Car Drivers - Race - Laps', 'fas fa-folder-open', RaceCarDriverRaceLap::class),
            MenuItem::linkToCrud('Race Car Drivers - Race - Pit Stops', 'fas fa-folder-open', RaceCarDriverRacePitStop::class),
            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fas fa-folder-open', User::class),
            MenuItem::linkToCrud('User Actions', 'fas fa-folder-open', UserAction::class),
            MenuItem::linkToCrud('User Devices', 'fas fa-folder-open', UserDevice::class),
            MenuItem::linkToCrud('User OAuth Providers', 'fas fa-folder-open', UserOauthProvider::class),
            MenuItem::linkToCrud('User TFA Methods', 'fas fa-folder-open', UserTfaMethod::class),
            MenuItem::linkToCrud('User TFA Recovery Codes', 'fas fa-folder-open', UserTfaRecoveryCode::class),
            MenuItem::linkToCrud('User Notifications', 'fas fa-folder-open', UserNotification::class),
            MenuItem::linkToCrud('User Blocks', 'fas fa-folder-open', UserBlock::class),
            MenuItem::linkToCrud('User Followers', 'fas fa-folder-open', UserFollower::class),
            MenuItem::linkToCrud('User Points', 'fas fa-folder-open', UserPoint::class),
            MenuItem::linkToCrud('User Exports', 'fas fa-folder-open', UserExport::class),
        ];
    }
}
