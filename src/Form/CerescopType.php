<?php

namespace App\Form;

use App\Entity\Cerescop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CerescopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class,[
                'label' => 'Image de la recette',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize : '16000k',
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
                'label' => 'Vidéo de la recette',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize : '16000k',
                        maxSizeMessage : 'Taille de fichier trop grande',
                        mimeTypes: [
                            'video/mp4',
                            'video/webm',
                            'video/ogg',
                            'video/avi',
                            'video/mpeg',
                        ],
                        mimeTypesMessage: "Extension de fichier invalide"
                    )
                ]
            ])
            ->add('nom', TextType::class,[
                'label' => 'Nom de la recette',
            ])
            ->add('description')
            ->add('date')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cerescop::class,
        ]);
    }
}
