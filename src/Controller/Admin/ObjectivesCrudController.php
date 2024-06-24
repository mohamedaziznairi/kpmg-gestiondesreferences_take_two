<?php

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
    
        if ($credentials instanceof Credentials) {
            $url = $this->adminUrlGenerator
                ->setController(CredentialsCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($credentials->getReferenceid()) // Ensure to use getId() for the entity ID
                ->generateUrl();
    
            // Redirect to detail page
            header('Location: ' . $url);
            exit; // Ensure script termination after header redirection
        }
    } 
}
