<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.Project.name'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'form.Project.description'
            ])
            ->add('location', TextType::class, [
                'label' => 'form.Project.location'
            ])
            ->add('type', EntityType::class, [
                'class' => ProjectType::class,
                'label' => 'form.Project.type'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
