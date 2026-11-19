<?php

namespace App\Form;

use App\Entity\Cerescop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CerescopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => 'Titre de l\'évènement',
            ])
            ->add('image', FileType::class,[
                'label' => 'Image de l\'évènement',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize : '5M',
                        maxSizeMessage : 'Taille de fichier trop grande',
                        mimeTypes: [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                            'image/webp',
                        ],
                        mimeTypesMessage: "Extension de fichier invalide"
                    )
                ]
            ])
            ->add('video' , FileType::class,[
                'label' => 'Vidéo de l\'évènement',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize :'5M',
                        maxSizeMessage : 'Taille de fichier trop grande',
                        mimeTypes: [
                            'video/mp4',
                            'video/webm',
                            'video/mkv',
                            'video/avi',
                            'video/mov',
                        ],
                        mimeTypesMessage: "Extension de fichier invalide",
                    )
                ]
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description de la vidéo',
            ])
            ->add('date', DateType::class,[
                'label' => 'Date de l\'évènement',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cerescop::class,
        ]);
    }
}
