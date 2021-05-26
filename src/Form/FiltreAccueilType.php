<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreAccueilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            //Permet d'obtenir la liste des campus.

            ->add('campus', EntityType::class,[
                'label' => 'Campus',
                'class' => Campus::class,
                'attr' => ['class' => 'campus-select'],
                'choice_label' => 'nom'
            ])
            //Permet de faire une recherche avec un nom.
            ->add('nom', TextType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'attr' => [
                    'placeholder' => 'Rechercher une sortie...'
                ]
                ])
            //choix de la date
            ->add('dateEntre', DateType::class,[
                'html5' => true,
                'widget' =>'single_text'
            ])
            //choix de la date
            ->add('dateET', DateType::class,[
                'html5' => true,
                'widget' =>'single_text'
            ])
            //permet de filtrer en fonction d'un choix de sorties.
            ->add('choix',ChoiceType::class,array(
                'choices'  => array(
                    'Sorties dont je suis l\'Organisateur/trice' => 1,
                    'Sorties auxquelles je suis suis inscrit/e' => 2,
                    'Sorties auxquelles je ne suis pas inscrit/e' => 3,
                    'Sorties passées' => 4
                ),
                'expanded' => true,
                'multiple' => true,
            ))


            //->add('rechercher', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campus::class,
        ]);
    }
}
