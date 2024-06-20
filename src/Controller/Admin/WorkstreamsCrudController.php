<?php
namespace App\Controller\Admin;
use App\Entity\Credentials;

use App\Entity\Workstreams;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

class WorkstreamsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Workstreams::class;
    }
       // Inject RequestStack and EntityManagerInterface
       private $requestStack;
       private $entityManager;
   
       public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
       {
           $this->requestStack = $requestStack;
           $this->entityManager = $entityManager;
       }
   
    public function createNewEntity(string $entityFqcn)
    {
        $entity = new Workstreams();

        // Fetch the referenceid parameter from the URL query string
        $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceid');

        // Set the referenceId in the Workstreams entity
        $entity->setReferenceid($referenceId);

        return $entity;
    }
 /*   public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('workstream'),
            TextField::new('workstream_VF'),
         //  AssociationField::new('referenceid'), // Hide on form if you don't want to edit it directly
         TextField::new('referenceid') // Pre-fill with URL parameter
     //    ->setFormTypeOption('disabled', true)
         ->setFormTypeOption('data', $this->requestStack->getCurrentRequest()->query->get('referenceId'))

        ];
    }

}*/
public function configureFields(string $pageName): iterable
{
    // Fetch the referenceid parameter from the URL query string
    $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceId');

    // Fetch the Credentials entity using Doctrine
    $credentials = $this->entityManager->getRepository(Credentials::class)->find($referenceId);
    return [
        TextField::new('workstream'),
        TextField::new('workstream_VF'),
        AssociationField::new('referenceid')
        //->setCrudController(CredentialsCrudController::class)
           ->setFormTypeOption('data_class',null) // Ensure correct data class
           ->setFormTypeOption('data', $credentials) // Pre-fill with Credentials entity
            //->setRequired(true) // Optional: make it required if needed
          // ->autocomplete(), // Optional: use autocomplete feature
    ];
}
}
/*namespace App\Controller\Admin;

use App\Entity\Credentials;
use App\Entity\Workstreams;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\RequestStack;

class WorkstreamsCrudController extends AbstractCrudController
{
    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Workstreams::class;
    }

    public function createNewEntity(string $entityFqcn)
    {
        $entity = new Workstreams();

        // Fetch the referenceid parameter from the URL query string
        $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceId');

        // Debugging purposes, you can remove this in production
        dump($referenceId);
        die;

        if ($referenceId) {
            // Fetch the Credentials entity using Doctrine
            $credentials = $this->entityManager->getRepository(Credentials::class)->find($referenceId);

            if ($credentials) {
                // Set the Credentials entity in the Workstreams entity
                $entity->setReferenceid($credentials);
            } else {
                throw $this->createNotFoundException('Credentials not found for referenceid ' . $referenceId);
            }
        }

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('workstream'),
            TextField::new('workstream_VF'),
            // Use AssociationField for referenceid
            AssociationField::new('referenceid')
            ->autocomplete()
            ->setRequired(true)
            ->setCrudController(CredentialsCrudController::class)
        
        ];
    }

    private function getReferenceIdAsEntity()
    {
        // Fetch the referenceid parameter from the URL query string
        $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceid');

        // Fetch the Credentials entity using Doctrine
        if ($referenceId) {
            return $this->entityManager->getRepository(Credentials::class)->find($referenceId);
        }

        return null;
    }
}
*/

