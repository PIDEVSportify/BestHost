<?php

namespace App\Form;

use App\Entity\Gerant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GerantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Id_gerant')
            ->add('Nom')
            ->add('Prenom')
            ->add('Date_nais')
            ->add('Ad_Email')
            ->add('Cin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gerant::class,
        ]);
    }
}
