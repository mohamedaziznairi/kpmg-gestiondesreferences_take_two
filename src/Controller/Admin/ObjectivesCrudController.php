<?php
namespace App\Controller\Admin;

use App\Entity\Credentials;
use App\Entity\Objectives;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ObjectivesCrudController extends AbstractCrudController
{
    private $requestStack;
    private $entityManager;
    private $adminUrlGenerator;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Objectives::class;
    }

    public function createNewEntity(string $entityFqcn)
    {
        $entity = new Objectives();

        // Fetch the referenceid parameter from the URL query string
        $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceId');

        if ($referenceId) {
            // Fetch the Credentials entity using Doctrine
            $credentials = $this->entityManager->getRepository(Credentials::class)->find($referenceId);

            if ($credentials instanceof Credentials) {
                $entity->setReferenceid($credentials);
            } else {
                throw $this->createNotFoundException('Credentials not found for referenceId ' . $referenceId);
            }
        }

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('objectif'),
            TextField::new('objectif_VF'),
            AssociationField::new('referenceid')
                ->setCrudController(CredentialsCrudController::class)
                ->setFormTypeOption('data_class', null)
                ->setFormTypeOption('data', $this->getReferenceIdAsEntity())
        ];
    }

    private function getReferenceIdAsEntity()
    {
        // Fetch the referenceid parameter from the URL query string
        $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceId');

        if ($referenceId) {
            // Fetch the Credentials entity using Doctrine
            return $this->entityManager->getRepository(Credentials::class)->find($referenceId);
        }

        return null;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof Objectives) {
            // Retrieve the Credentials entity linked to this Objectives
            $credentials = $entityInstance->getReferenceid();

            if ($credentials instanceof Credentials) {
                // Generate URL for Credentials detail page
                $url = $this->adminUrlGenerator
                    ->setController(CredentialsCrudController::class)
                    ->setAction(Action::DETAIL)
                    ->setEntityId($credentials->getReferenceid()) // Ensure to use getId() for the entity ID
                    ->generateUrl();

                // Redirect to the detail page
                $response = new RedirectResponse($url);
                $response->send();
                exit; // Ensure script termination after header redirection
            }
        }
    }
}

/*
namespace App\Controller\Admin;
use App\Entity\Credentials;

use App\Entity\Objectives;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
class ObjectivesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objectives::class;
    }

    // Inject RequestStack and EntityManagerInterface
    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function configureFields(string $pageName): iterable
    {
        // Fetch the referenceid parameter from the URL query string
    $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceId');

    // Fetch the Credentials entity using Doctrine
    $credentials = $this->entityManager->getRepository(Credentials::class)->find($referenceId);
        return [
           
            TextField::new('objectif'),
            TextField::new('objectif_VF'),
           // AssociationField::new('referenceId'),
           AssociationField::new('referenceid')
           //->setCrudController(CredentialsCrudController::class)
              ->setFormTypeOption('data_class',null) // Ensure correct data class
              ->setFormTypeOption('data', $credentials) // Pre-fill with Credentials entity
               //->setRequired(true) // Optional: make it required if needed
             // ->autocomplete(), // Optional: use autocomplete feature
          
        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);
    
        if ($entityInstance instanceof Workstreams) {
            // Retrieve the Credentials entity linked to this Workstreams
            $credentials = $entityInstance->getReferenceid();
            if ($credentials instanceof Credentials) {
                $url = $this->adminUrlGenerator
                    ->setController(CredentialsCrudController::class)
                    ->setAction(Action::DETAIL)
                    ->setEntityId($credentials->getReferenceid()) // Ensure to use getId() for the entity ID
                    ->generateUrl();
                // Redirect to the detail page
                header('Location: ' . $url);
                exit; // Ensure script termination after header redirection
            }
    } 
    }
}*/
