<?php

namespace App\Controller\Admin;

use App\Entity\Credentials;
use App\Entity\Objectives ;
use App\Entity\Workstreams;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Doctrine\ORM\EntityManagerInterface;

class CredentialsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Credentials::class;
    }
   /* private $adminUrlGenerator;

    public function __construct1(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }*/
    private $adminUrlGenerator;
    private $powerPointGenerator;
    private $entityManager;

 
    
    public function __construct(AdminUrlGenerator $adminUrlGenerator, PowerPointGeneratorService $powerPointGenerator, EntityManagerInterface $entityManager)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->powerPointGenerator = $powerPointGenerator;
        $this->entityManager = $entityManager;

    }
   /* public function configureActions(Actions $actions): Actions
    {
        $generatePpt = Action::new('generatePpt', 'Generate PPT')
            ->linkToRoute('admin_credentials_generate_powerpoint', function (Credentials $entity): array {
                return [
                    'referenceId' => $entity->getReferenceid(), // Adjust if 'getId' is not the method to get the reference ID
                ];
            })
            ->setCssClass('btn btn-success');

        return $actions
            ->add(Crud::PAGE_INDEX, $generatePpt)
            ->add(Crud::PAGE_DETAIL, $generatePpt);
    }*/
    public function configureActions(Actions $actions): Actions
    {
        $generatePpt = Action::new('generatePpt', 'Generate PPT')
            ->linkToRoute('admin_credentials_generate_powerpoint', function (Credentials $entity): array {
                return [
                    'referenceId' => $entity->getReferenceid(),
                ];
            })
            ->setCssClass('btn btn-success');

       /* $addActionObjective = Action::new('addObjective', 'Add Objective')
            ->linkToCrudAction(Crud::PAGE_NEW, 'objectives')
            ->setCssClass('btn btn-secondary'); // Adjust CSS class as needed

        $addActionWorkstream = Action::new('addWorkstream', 'Add Workstream')
            ->linkToCrudAction(Crud::PAGE_NEW, 'workstreams')
            ->setCssClass('btn btn-secondary'); // Adjust CSS class as needed  
*/
$addObjective = Action::new('addObjective', 'Add Objective')
->linkToUrl(function (Credentials $entity) {
    $url = $this->adminUrlGenerator->setController(ObjectivesCrudController::class)
        ->setAction(Crud::PAGE_NEW)
        ->set('referenceId', $entity->getReferenceid())
        ->generateUrl();

    return $url;
})
->setCssClass('btn btn-secondary');

$addWorkstream = Action::new('addWorkstream', 'Add Workstream')
->linkToUrl(function (Credentials $entity) {
    $url = $this->adminUrlGenerator->setController(WorkstreamsCrudController::class)
        ->setAction(Crud::PAGE_NEW)
        ->set('referenceId', $entity->getReferenceid())
        ->generateUrl();

    return $url;
})
->setCssClass('btn btn-secondary');



        return $actions
            ->add(Crud::PAGE_INDEX, $generatePpt)
            ->add(Crud::PAGE_DETAIL, $generatePpt)
       //      ->add(Crud::PAGE_INDEX, $addActionObjective)
       //     ->add(Crud::PAGE_INDEX, $addActionWorkstream)
             // Add to PAGE_DETAIL to display under the detail view
             ->add(Crud::PAGE_DETAIL, $addObjective)
             ->add(Crud::PAGE_DETAIL, $addWorkstream)
             ->add(Crud::PAGE_INDEX, $addObjective)
             ->add(Crud::PAGE_INDEX, $addWorkstream);
            
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('country'),
            TextField::new('ProjectTitle'),
            TextEditorField::new('description')->setFormTypeOptions(['attr' => ['class' => 'ckeditor']]),
            TextField::new('countryvf'),
            TextField::new('ProjectTitlevf'),
           // TextEditorField::new('descriptionvf'),
            TextEditorField::new('descriptionvf')->setFormTypeOptions(['attr' => ['class' => 'ckeditor']])
            ,
            AssociationField::new('client'),
            //AssociationField::new('userid'),
           // Add custom fields for Objectives and Workstreams
          
            
        ];
    }
  /*  private $powerPointGenerator;

    public function __construct(PowerPointGeneratorService $powerPointGenerator)
    {
        $this->powerPointGenerator = $powerPointGenerator;
    }*/


/*#[Route('/admin/generate-powerpoint/{referenceId}', name: 'admin_credentials_generate_powerpoint')]
public function generatePowerPoint(string $referenceId): Response
{
    // Fetch the Credentials entity using ParamConverter
    $credentials = $this->getDoctrine()->getRepository(Credentials::class)->findOneBy(['referenceid' => $referenceId]);

    if (!$credentials) {
        throw $this->createNotFoundException('Credentials not found for referenceId ' . $referenceId);
    }

    // Example: Get data from the selected credential
    $credentialData = [
        'country' => $credentials->getCountry(),
        'project title ' => $credentials->getProjecttitle(),
        'description ' => $credentials->getDescription(),
        'client' => $credentials->getClient()->getCompanyname(),
        

         // Assuming 'getClient' returns an object and 'getName' gets the client's name
        // Add more fields as needed
        
    ];

    // Generate the PowerPoint presentation using your service
    $filename = $this->powerPointGenerator->generatePresentation($credentialData);

    // Return a response with the file download
    return $this->file($filename, 'credential_'.$credentials->getReferenceid().'.pptx');
}
*/

#[Route('/admin/generate-powerpoint/{referenceId}', name: 'admin_credentials_generate_powerpoint')]
public function generatePowerPoint(string $referenceId): Response
{
    // Fetch the Credentials entity
    $credentials = $this->entityManager->getRepository(Credentials::class)->findOneBy(['referenceid' => $referenceId]);

    if (!$credentials) {
        throw $this->createNotFoundException('Credentials not found for referenceId ' . $referenceId);
    }

    // Fetch all Objectives associated with the Credentials
    $objectives = $this->entityManager->getRepository(Objectives::class)->findBy(['referenceid' => $referenceId]);

     // Fetch all Objectives associated with the Credentials
     $Workstreams = $this->entityManager->getRepository(Workstreams::class)->findBy(['referenceid' => $referenceId]);

    // Collect data from the Credentials entity
    $credentialData = [
        'country' => $credentials->getCountry(),
        'client' => $credentials->getClient()->getCompanyname(), // Ensure `getCompanyname()` is the correct method
        'project_title' => $credentials->getProjecttitle(),
        'description' => $credentials->getDescription(),
        'objectives' => $objectives, // Store the whole objectives array
        'Workstreams' => $Workstreams // Store the whole objectives array

    ];

    // Generate the PowerPoint presentation using your service
    $filename = $this->powerPointGenerator->generatePresentation($credentialData);

    // Return a response with the file download
    return $this->file($filename, 'credential_' . $credentials->getReferenceid() . '.pptx');
}
}