<?php

namespace App\Form\App;

use App\Entity\App\AppUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppProfileUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            #> guardado
            ->add('image' , FileType::class, [
                'label' => 'Imagen',
                'mapped' => false,
                'data_class'=> null,
                'required' => false,
                'attr' => [
                    'class' => 'custom-file-input',
                    'measure'=> '150x150 px a 72 dpi',
                    'weight'=> '100 kb',
                    'avatar'=> 'user.png',
                ],
                'constraints' => [
                    /*new Image([
                        'maxWidth' => 150,
                        'maxWidthMessage' => 'El ancho de la imagen es demasiado grande ({{ width }}px). El ancho máxima permitido es {{ max_width }}px',
                        'maxHeight' => 150,
                        'maxHeightMessage' => 'La altura de la imagen es demasiado grande ({{ height }}px). La altura máxima permitida es {{ max_height }}px',
                    ]),
                    new File([
                        'maxSize' => '100k',
                        'maxSizeMessage' => 'La imagen es demasiado grande ({{ size }} {{ suffix }}). El tamaño máximo permitido es {{ limit }} {{ suffix }}.',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'El tipo de imagen no es válido ({{ type }}). Los tipos de mímica permitidos son {{ types }}.',
                    ])*/
                ],
            ])
            ->add('name', TextType::class, [
                'required'  => true,
                'label'     => 'Nombre',
                'attr'      => [
                    'class'         => 'form-control border-left-0 bg-transparent pl-0',
                    'placeholder'   => 'Nombre',
                    'icon' => 'fal fa-keyboard'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                ],
            ])
            ->add('surname', TextType::class, [
                'required'  => true,
                'label'     => 'Apellido',
                'attr'      => [
                    'class'         => 'form-control border-left-0 bg-transparent pl-0',
                    'placeholder'   => 'Apellido',
                    'icon' => 'fal fa-keyboard'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                ],
            ])
            ->add('email', EmailType::class, [
                'required'  => true,
                'label'     => 'Correo',
                'attr'      => [
                    'class'         => 'form-control border-left-0 bg-transparent pl-0',
                    'placeholder'   => 'Correo',
                    'icon' => 'fal fa-envelope'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                ],
            ])
            ->add('pass', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Las contraseñas no son iguales.',
                'options' => [
                    'attr' => [
                        'class' => 'form-control border-left-0 bg-transparent pl-0',
                        'icon' => 'fal fa-key'
                    ]
                ],
                'required' => false,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repita la contraseña'],
            ])

            #> Botones.
            ->add('save',SubmitType::class,[
                'label' => '<i class="fal fa-save mr-1"></i> Guardar datos',
                'attr'  => [
                    'class'     => 'btn btn-primary waves-effect waves-themed'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppUser::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['email'],
                    'message' => 'El Correo: {{ value }} ya se esta utilizado. '
                ])
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
