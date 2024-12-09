<?php

namespace App\Form;

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
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'required' => true,
                'label' => 'app.form.difficulty',
                'choices' => $this->getChoice(),
            ])
            ->add('synopsis', TextareaType::class, []);
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