<?php

namespace App\Form;

use App\Entity\Contact;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstname',TextType::class,array('attr'=>array('placeholder'=>'firstname'))
            )
            ->add('lastname',TextType::class,array('attr'=>array('placeholder'=>'firstname')))

            ->add('phone')
            ->add('email')
            ->add('message',TextareaType::class)

            

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
