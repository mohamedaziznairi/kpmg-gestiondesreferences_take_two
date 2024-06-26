<?php

namespace App\Controller\Admin;

use App\Entity\Credentials;
use App\Entity\Objectives;
use App\Entity\Workstreams;
use App\Entity\Users;
use Symfony\Component\Security\Core\Security;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\ExpressionLanguage\Expression;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
class CredentialsCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;
    private $powerPointGenerator;
    private $entityManager;
    private $security;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, PowerPointGeneratorService $powerPointGenerator, EntityManagerInterface $entityManager,Security $security)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->powerPointGenerator = $powerPointGenerator;
        $this->entityManager = $entityManager;
        $this->security = $security;

    }

    public static function getEntityFqcn(): string
    {
        return Credentials::class;
    }

   
    public function configureActions(Actions $actions): Actions
    {
        $generatePpt = Action::new('generatePpt', 'Generate PPT')
            ->linkToRoute('admin_credentials_generate_powerpoint', function (Credentials $entity): array {
                return [
                    'referenceId' => $entity->getReferenceid(),
                ];
            })
            ->setCssClass('btn btn-success');
            $generatePptvf = Action::new('generatePptvf', 'Generate PPT vf')
            ->linkToRoute('admin_credentials_generate_powerpointvf', function (Credentials $entity): array {
                return [
                    'referenceId' => $entity->getReferenceid(),
                ];
            })
            ->setCssClass('btn btn-success');
        $addObjective = Action::new('addObjective', 'Add Objective')
            ->linkToUrl(function (Credentials $entity) {
                if ($entity === null) {
                    throw new \LogicException('The entity is not available.');
                }
                return $this->adminUrlGenerator->setController(ObjectivesCrudController::class)
                    ->setAction(Crud::PAGE_NEW)
                   ->set('referenceId', $entity->getReferenceid())
                   ->setEntityId(null) // Explicitly set entityId to null
                    ->generateUrl();
            })
            ->setCssClass('btn btn-secondary');

        $addWorkstream = Action::new('addWorkstream', 'Add Workstream')
            ->linkToUrl(function (Credentials $entity) {
                if ($entity === null) {
                    throw new \LogicException('The entity is not available.');
                }
                return $this->adminUrlGenerator->setController(WorkstreamsCrudController::class)
                    ->setAction(Crud::PAGE_NEW)
                    ->set('referenceId', $entity->getReferenceid())
                    ->setEntityId(null) // Explicitly set entityId to null
                    ->generateUrl();
            })
            ->setCssClass('btn btn-secondary');
            $viewPowerPointTemplate = Action::new('viewPowerPointTemplate', 'View PowerPoint Template')
            ->linkToRoute('admin_powerpoint_template', function (Credentials $entity) {
                // Replace 'custom_route_name' with the name of your route
                return [
                    'referenceId' => $entity->getReferenceid(),
                ];
            })
            ->setCssClass('btn btn-primary'); // Set your desired CSS classes
            $viewPowerPointTemplatevf = Action::new('viewPowerPointTemplatevf', 'View PowerPoint Templatevf')
            ->linkToRoute('admin_powerpoint_templatevf', function (Credentials $entity) {
                // Replace 'custom_route_name' with the name of your route
                return [
                    'referenceId' => $entity->getReferenceid(),
                ];
            })
            ->setCssClass('btn btn-primary'); // Set your desired CSS classes

            // View Objectives action
            $viewObjectives = Action::new('viewObjectives', 'View Objectives')
            ->linkToUrl(function (Credentials $entity) {
                if ($entity === null) {
                    throw new \LogicException('The entity is not available.');
                }
                return $this->adminUrlGenerator
                    ->setController(ObjectivesCrudController::class)
                    ->setAction(Crud::PAGE_INDEX)
                    ->setEntityId(null) // Ensure we don't target a specific entity
                    ->set('filters', ['referenceid' => $entity->getReferenceid()]) // Pass as a filter
                    ->generateUrl();
            })
            ->setCssClass('btn btn-primary');
            $viewWorkstreams = Action::new('viewWorkstreams', 'View Workstreams')
            ->linkToUrl(function (Credentials $entity) {
                if ($entity === null) {
                    throw new \LogicException('The entity is not available.');
                }
                return $this->adminUrlGenerator
                    ->setController(WorkstreamsCrudController::class)
                    ->setAction(Crud::PAGE_INDEX)
                    ->setEntityId(null) // Ensure we don't target a specific entity
                    ->set('filters', ['referenceid' => $entity->getReferenceid()]) // Pass as a filter
                    ->generateUrl();
            })
            ->setCssClass('btn btn-primary');

   
        return $actions
            ->add(Crud::PAGE_INDEX, $viewPowerPointTemplate)
            ->add(Crud::PAGE_INDEX, $viewPowerPointTemplatevf)
            ->add(Crud::PAGE_DETAIL, $generatePpt)
            ->add(Crud::PAGE_INDEX, $generatePpt)
            ->add(Crud::PAGE_INDEX, $generatePptvf)
            ->add(Crud::PAGE_DETAIL, $viewWorkstreams)
            ->add(Crud::PAGE_INDEX, $viewWorkstreams)
            ->add(Crud::PAGE_DETAIL, $viewObjectives)
            ->add(Crud::PAGE_INDEX, $viewObjectives)
            ->add(Crud::PAGE_DETAIL, $addObjective)
            ->add(Crud::PAGE_INDEX, $addWorkstream)
            ->add(Crud::PAGE_INDEX, $addObjective)
            ->add(Crud::PAGE_DETAIL, $addWorkstream);

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('country'),
            TextField::new('ProjectTitle'),
            TextEditorField::new('description')->setFormTypeOptions(['attr' => ['class' => 'ckeditor']]),
            TextField::new('countryvf'),
            TextField::new('ProjectTitlevf'),
            TextEditorField::new('descriptionvf')->setFormTypeOptions(['attr' => ['class' => 'ckeditor']]),
         //   AssociationField::new('client'),
      /*   AssociationField::new('client')->formatValue(function ($value, $entity) {
           return $entity->getClient()->getCompanyname();
      }),
      AssociationField::new('client')
      ->setFormTypeOptions([
          'choice_label' => 'companyname', // Assuming 'companyname' is the property of Client entity you want to display
      ]),*/
      AssociationField::new('client')
      ->setFormTypeOptions([
          'choice_label' => 'companyname', // Display the company name in the form
      ])
      ->formatValue(function ($value, $entity) {
          return $entity->getClient()->getCompanyname(); // Display the company name in the index
      }),
        ];
    }

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
        $workstreams = $this->entityManager->getRepository(Workstreams::class)->findBy(['referenceid' => $referenceId]);

        // Collect data from the Credentials entity
        $credentialData = [
            'country' => $credentials->getCountry(),
            'project_title' => $credentials->getProjecttitle(),
            'description' => $credentials->getDescription(),
            'client' => $credentials->getClient()->getCompanyname(), // Ensure `getCompanyname()` is the correct method
            'objectives' => $objectives, // Store the whole objectives array
            'workstreams' => $workstreams // Store the whole objectives array

        ];
    
        // Generate the PowerPoint presentation using your service
        $filename = $this->powerPointGenerator->generatePresentation($credentialData);
    
        // Return a response with the file download
        return $this->file($filename, 'credential_' . $credentials->getReferenceid() . '.pptx');
    }
    #[Route('/admin/generate-powerpointvf/{referenceId}', name: 'admin_credentials_generate_powerpointvf')]
    public function generatePowerPointvf(string $referenceId): Response
    {
        // Fetch the Credentials entity
        $credentials = $this->entityManager->getRepository(Credentials::class)->findOneBy(['referenceid' => $referenceId]);
    
        if (!$credentials) {
            throw $this->createNotFoundException('Credentials not found for referenceId ' . $referenceId);
        }
    
        // Fetch all Objectives associated with the Credentials
        $objectives = $this->entityManager->getRepository(Objectives::class)->findBy(['referenceid' => $referenceId]);
        $workstreams = $this->entityManager->getRepository(Workstreams::class)->findBy(['referenceid' => $referenceId]);
        

        // Collect data from the Credentials entity
        $credentialData = [
            'country' => $credentials->getCountryVf(),
            'project_title' => $credentials->getProjecttitleVf(),
            'description' => $credentials->getDescriptionVf(),
            'client' => $credentials->getClient()->getCompanyname(), // Ensure `getCompanyname()` is the correct method
            'objectives' => $objectives, // Store the whole objectives array
            'workstreams' => $workstreams // Store the whole objectives array

        ];
    
        // Generate the PowerPoint presentation using your service
        $filename = $this->powerPointGenerator->generatePresentationvf($credentialData);
    
        // Return a response with the file download
        return $this->file($filename, 'credential_' . $credentials->getReferenceid() . '.pptx');
    }
    #[Route('/admin/powerpointTemplate/{referenceId}', name: 'admin_powerpoint_template', methods: ['GET'])]
    public function viewPowerPointTemplate(string $referenceId): Response
    {
        // Fetch the credential entity from the database based on referenceId
        $credential = $this->getDoctrine()->getRepository(Credentials::class)->findOneBy(['referenceid' => $referenceId]);
  // Fetch all Objectives associated with the Credentials
  $objectives = $this->entityManager->getRepository(Objectives::class)->findBy(['referenceid' => $referenceId]);
  $workstreams = $this->entityManager->getRepository(Workstreams::class)->findBy(['referenceid' => $referenceId]);
        if (!$credential) {
            throw $this->createNotFoundException('Credential not found');
        }

        // Render the Twig template and pass the credential entity as data
        return $this->render('powerpointTemplate.html.twig', [
            'credential' => $credential,
            'objectives' => $objectives,
            'workstreams' => $workstreams,

        ]);
    }
    #[Route('/admin/powerpointTemplatevf/{referenceId}', name: 'admin_powerpoint_templatevf', methods: ['GET'])]
    public function viewPowerPointTemplatevf(string $referenceId): Response
    {
        // Fetch the credential entity from the database based on referenceId
        $credential = $this->getDoctrine()->getRepository(Credentials::class)->findOneBy(['referenceid' => $referenceId]);
  // Fetch all Objectives associated with the Credentials
  $objectives = $this->entityManager->getRepository(Objectives::class)->findBy(['referenceid' => $referenceId]);
  $workstreams = $this->entityManager->getRepository(Workstreams::class)->findBy(['referenceid' => $referenceId]);
        if (!$credential) {
            throw $this->createNotFoundException('Credential not found');
        }

        // Render the Twig template and pass the credential entity as data
        return $this->render('powerpointTemplateVF.html.twig', [
            'credential' => $credential,
            'objectives' => $objectives,
            'workstreams' => $workstreams,

        ]);
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof Credentials) {
            $url = $this->adminUrlGenerator
                ->setController(self::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($entityInstance->getReferenceid())
                ->generateUrl();

            // Redirect to detail page
            header('Location: ' . $url);
            exit;
        }
    }
 
   
   /* public function configureCrud(Crud $crud): Crud
    {
        // Get the ID of the logged-in user
        $loggedInUserId = $this->security->getUser()->getUserid();

        return $crud
         //   ->setDefaultSort(['id' => 'DESC']) // Optional: Default sorting
            ->overrideQuery(function ($queryBuilder) use ($loggedInUserId) {
                // Modify the query to filter by the logged-in user's ID
                $queryBuilder
                    ->andWhere('entity.userid = :userId')
                    ->setParameter('userId', $loggedInUserId);
            });
    }*/


 /*   public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $user = $this->security->getUser();

        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        return $queryBuilder
            ->andWhere('entity.userid = :userid')
            ->setParameter('userid', $user);
    }*/

    public function createIndexQueryBuilder(
        $searchDto,
        $entityDto,
        $fields,
        $filters
    ): QueryBuilder {
        $user = $this->security->getUser();
        $userRole = $user->getRole(); // Assume this returns a single role as a string
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($userRole === 'ROLE_USER') {
            $queryBuilder
            ->andWhere('entity.userid = :userid')
                ->setParameter('userid', $user);
            }

        return $queryBuilder;
    }

    
   
}
