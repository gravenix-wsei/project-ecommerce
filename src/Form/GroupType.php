<?php

namespace App\Form;

use App\Entity\AdministrationUser;
use App\Entity\Group;
use App\System\Administration\Permission\PrivilegesServiceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('privileges', ChoiceType::class, [
                'choices' => PrivilegesServiceInterface::ALL_PRIVILEGES,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('users', EntityType::class, [
                'class' => AdministrationUser::class,
                'choice_label' => 'username',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
