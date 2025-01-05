<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class VideoGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'required' => true,
                'label' => 'Nom',
            ])
            ->add('difficulty', ChoiceType::class, [
                'required' => true,
                'label' => 'Difficulté',
                'choices' => $this->getChoice(),
            ])
            ->add('synopsis', TextareaType::class, [])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
        ;
    }

    protected function getChoice(): array
    {
        $choices = [];
        for ($i = 1; $i <= 5; $i++) {
            $choices[$i] = $i;
        }
        return $choices;
    }
}