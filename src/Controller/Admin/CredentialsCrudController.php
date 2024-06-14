<?php

namespace App\Controller\Admin;

use App\Entity\Credentials;
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
class CredentialsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Credentials::class;
    }
    public function configureActions(Actions $actions): Actions
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

            
        ];
    }
    private $powerPointGenerator;

    public function __construct(PowerPointGeneratorService $powerPointGenerator)
    {
        $this->powerPointGenerator = $powerPointGenerator;
    }

    /**
     * @Route("/admin/generate-powerpoint/{id}", name="admin_generate_powerpoint")
     */
  /*  #[Route('/admin/generate-powerpoint', name: 'admin_generate_powerpoint')]

    public function generatePowerPoint(Request $request): Response
    {
        // Generate the PowerPoint presentation
        $filename = $this->powerPointGenerator->generatePresentation();

        // Return a response with the file download
        return $this->file($filename, 'hello.pptx');
    }*/
 /*   #[Route('/admin/generate-powerpoint/{referenceId}', name: 'admin_credentials_generate_powerpoint')]
    public function generatePowerPoint(Credentials $credentials): Response
    {
      //  dump($credentials);
        // Example: Get data from the selected credential
        $credentialData = [
            'country' => $credentials->getCountry(),
            'project title ' => $credentials->getProjecttitle(),
            'description ' => $credentials->getDescription(),
            'Client' => $credentials->getClient(),
            // Add more fields as needed
        ];

        // Generate the PowerPoint presentation using your service
        $filename = $this->powerPointGenerator->generatePresentation($credentialData);

        // Return a response with the file download
        return $this->file($filename, 'credential_'.$credentials->getReferenceid().'.pptx');
    }*/

#[Route('/admin/generate-powerpoint/{referenceId}', name: 'admin_credentials_generate_powerpoint')]
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
        'client' => $credentials->getClient()->getCompanyname(), // Assuming 'getClient' returns an object and 'getName' gets the client's name
        // Add more fields as needed
    ];

    // Generate the PowerPoint presentation using your service
    $filename = $this->powerPointGenerator->generatePresentation($credentialData);

    // Return a response with the file download
    return $this->file($filename, 'credential_'.$credentials->getReferenceid().'.pptx');
}

}
