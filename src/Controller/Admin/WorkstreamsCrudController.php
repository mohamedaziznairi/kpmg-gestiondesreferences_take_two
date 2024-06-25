<?php
namespace App\Controller\Admin;

use App\Entity\Credentials;
use App\Entity\Workstreams;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\ExpressionLanguage\Expression;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
class WorkstreamsCrudController extends AbstractCrudController
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
        return Workstreams::class;
    }

    public function createNewEntity(string $entityFqcn)
    {
        $entity = new Workstreams();
        $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceId');

        if ($referenceId) {
            $credentials = $this->entityManager->getRepository(Credentials::class)->find($referenceId);
            if ($credentials) {
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
            TextField::new('workstream'),
            TextField::new('workstream_VF'),
            AssociationField::new('referenceid')
                ->setCrudController(CredentialsCrudController::class)
                ->setFormTypeOption('data_class', null)
                ->setFormTypeOption('data', $this->getReferenceIdAsEntity())
        ];
    }

    private function getReferenceIdAsEntity()
    {
        $referenceId = $this->requestStack->getCurrentRequest()->query->get('referenceId');

        if ($referenceId) {
            return $this->entityManager->getRepository(Credentials::class)->find($referenceId);
        }

        return null;
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
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $request = $this->getContext()->getRequest();
        $filterData = $request->query->get('filters');

        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if (!empty($filterData['referenceid'])) {
            $queryBuilder
                ->andWhere('entity.referenceid = :referenceId')
                ->setParameter('referenceId', $filterData['referenceid']);
        }

        return $queryBuilder;
    }
}
