<?php

namespace App\Form;

use App\Entity\Credentials;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CredentialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country')
            ->add('projecttitle')
            ->add('description')
            ->add('countryVf')
            ->add('projecttitleVf')
            ->add('descriptionVf')
            ->add('userid')
            ->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Credentials::class,
        ]);
    }
}
