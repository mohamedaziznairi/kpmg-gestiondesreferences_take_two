<?php

// src/Controller/Admin/UsersCrudController.php

namespace App\Controller\Admin;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsersCrudController extends AbstractCrudController
{
    private $passwordEncoder;
    private $slugger;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Define choices for the role field dropdown
    $roleChoices = [
        'User' => 'ROLE_USER',
        'Admin' => 'ROLE_ADMIN', ];
       
        $creationdate = (new \DateTime());

        return [
            TextField::new('firstname'),
            TextField::new('lastname'),
            Field::new('creationdate')
                ->setFormattedValue($creationdate),
                Field::new('role')
                ->setFormType(ChoiceType::class)
                ->setFormTypeOptions([
                    'choices' => $roleChoices,
                ]),
            TextField::new('email'),
            Field::new('password')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\PasswordType::class) // Use PasswordType for password input
                ->setFormTypeOptions([
                    'empty_data' => '', // Ensure password is not pre-filled
                ])
                ->onlyOnForms(), // Display only on forms, not on index/detail pages
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Set creation date to current datetime
        if ($entityInstance instanceof Users) {
            $entityInstance->setCreationdate(new \DateTime());
            
            // Encrypt the password before persisting the entity
            $plainPassword = $entityInstance->getPassword();
            $encodedPassword = $this->passwordEncoder->encodePassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($encodedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
