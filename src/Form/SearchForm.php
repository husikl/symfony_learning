<?php

namespace App\Form;

use App\Model\ArticleFilter;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('id',IntegerType::class, [
                'required'=>false,
                'label' => 'By ID'
            ])
            ->add('date',DateType::class,[
                'required'=>false,
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd',
                'input' => 'datetime_immutable',
                'label' => 'By Date'
             ])
            ->add('authors',TextType::class,[
                'required'=>false,
                'constraints' => [
                    new Length(['min' => 3,'max'=>10])
                ],
                'help' => 'Search by authors name.',
            ])
            ->add('content',TextType::class,[
                'required'=>false,
                'help'=>'Search by content.',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', ArticleFilter::class);
        $resolver->setDefault('method', 'get');
        $resolver->setDefault('csrf_protection', false);
    }
}