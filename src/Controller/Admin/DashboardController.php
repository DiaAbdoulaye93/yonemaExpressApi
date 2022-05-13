<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AdminSystem;
use App\Entity\Caissier;
use App\Entity\Profile;
use App\Entity\User;
use App\Entity\UserAgence;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Transfermobile');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Profile user', 'fas fa-list', Profile::class);
        yield MenuItem::linkToCrud('adminSystem', 'fas fa-list', AdminSystem::class);
        yield MenuItem::linkToCrud('Caissier', 'fas fa-list', Caissier::class);  
        yield MenuItem::linkToCrud('User Agence', 'fas fa-list', UserAgence::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
      
   
    }
}
