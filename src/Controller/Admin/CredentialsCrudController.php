<?php

namespace App\Controller\Admin;

use App\Entity\Credentials;
use App\Entity\Objectives;
use App\Entity\Workstreams;
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

class CredentialsCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;
    private $powerPointGenerator;
    private $entityManager;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, PowerPointGeneratorService $powerPointGenerator, EntityManagerInterface $entityManager)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->powerPointGenerator = $powerPointGenerator;
        $this->entityManager = $entityManager;
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

        // Attach actions only to DETAIL pages where the entity is guaranteed to be present
        return $actions
            ->add(Crud::PAGE_DETAIL, $generatePpt)
            ->add(Crud::PAGE_DETAIL, $addObjective)
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
            AssociationField::new('client'),
        ];
    }

    #[Route('/admin/generate-powerpoint/{referenceId}', name: 'admin_credentials_generate_powerpoint')]
    public function generatePowerPoint(string $referenceId): Response
    {
        $credentials = $this->entityManager->getRepository(Credentials::class)->findOneBy(['referenceid' => $referenceId]);

        if (!$credentials) {
            throw $this->createNotFoundException('Credentials not found for referenceId ' . $referenceId);
        }

        $objectives = $this->entityManager->getRepository(Objectives::class)->findBy(['referenceid' => $referenceId]);
        $workstreams = $this->entityManager->getRepository(Workstreams::class)->findBy(['referenceid' => $referenceId]);

        $credentialData = [
            'country' => $credentials->getCountry(),
            'client' => $credentials->getClient()->getCompanyname(),
            'project_title' => $credentials->getProjecttitle(),
            'description' => $credentials->getDescription(),
            'objectives' => $objectives,
            'workstreams' => $workstreams,
        ];

        $filename = $this->powerPointGenerator->generatePresentation($credentialData);

        return $this->file($filename, 'credential_' . $credentials->getReferenceid() . '.pptx');
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
}
