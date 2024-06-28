<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Security\Core\Security;
use App\Entity\Users;
use App\Entity\Credentials;
use App\Entity\Clients;

class DashboardController extends AbstractDashboardController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $user = $this->security->getUser();
        $userRole = $user->getRoles(); // Assume this returns a single role as a string
        
        if ($userRole === 'ROLE_ADMIN') {
            $url = $adminUrlGenerator->setController(CredentialsCrudController::class)->generateUrl();
        } elseif ($userRole === 'ROLE_USER') {
            $url = $adminUrlGenerator->setController(CredentialsCrudController::class)->generateUrl();
        } else {
            // Redirect to some default page or show error if role is not recognized
            $url = $adminUrlGenerator->setController(CredentialsCrudController::class)->generateUrl();
        }

        return $this->redirect($url);
    }

    #[Route('/superadmin', name: 'superadmin_dashboard')]
    public function indexSuperadmin(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $url = $adminUrlGenerator->setController(UsersCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion des Références KPMG');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $user = $this->security->getUser();
        $userRole = $user->getRole(); // Assume this returns a single role as a string
        
        if ($userRole === 'ROLE_ADMIN') {
        yield MenuItem::linkToCrud('Users', 'fas fa-users', Users::class);
        yield MenuItem::linkToCrud('Clients', 'fa fa-group', Clients::class);
    }
        yield MenuItem::linkToCrud('Credentials', 'fa fa-address-book', Credentials::class);
        yield MenuItem::linkToRoute('PowerPoint Template', 'fa fa-file', 'powerpointTemplate');
    }
}
