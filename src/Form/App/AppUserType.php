<?php

namespace App\Form\App;

use App\Entity\App\AppProfile;
use App\Entity\App\AppUser;
use App\Form\App\EventListener\AppCountryEventEventSubscriber;
use App\Form\App\EventListener\AppDepartmentEventSubscriber;
use App\Form\App\EventListener\AppProvinceEventSubscriber;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            #> guardado
            ->add('image' , FileType::class, [
                'label' => 'Imagen',
                'data_class'=> null,
                'required' => false,
                'attr' => [
                    'class' => 'custom-file-input',
                    'measure'=> '150x150 px a 72 dpi',
                    'weight'=> '100 kb',
                    'avatar'=> 'user.png',
                ],
                'constraints' => [
                    /*
                    new Image([
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
                    ])
                    */
                ],
            ])
            ->add('name', TextType::class, [
                'required'  => true,
                'label'     => 'Nombres',
                'label_attr' => [

                ],
                'attr'      => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Nombres',
                    'icon' => 'fal fa-keyboard'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                ],
            ])
            ->add('surname', TextType::class, [
                'required'  => true,
                'label'     => 'Apellidos',
                'label_attr' => [

                ],
                'attr'      => [
                    'class'         => 'form-control border-left-0 bg-transparent pl-0',
                    'placeholder'   => 'Apellidos',
                    'icon' => 'fal fa-keyboard'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                ],
            ])
            ->add('email', EmailType::class, [
                'required'  => true,
                'label'     => 'Correo',
                'label_attr' => [

                ],
                'attr'      => [
                    'class'         => 'form-control border-left-0 bg-transparent pl-0',
                    'placeholder'   => 'Correo',
                    'icon' => 'fal fa-envelope'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                ],
            ])
            ->add('appProfile', EntityType::class, [
                'class'         => AppProfile::class,
                'required'      => true,
                'label'         => 'Perfil de usuario',
                'placeholder'   => 'Seleccione el perfil de usuario',
                'attr'          => [
                    'class' => 'form-control'
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ap')
                        ->where("ap.flagDelete = :flagDelete")
                        ->andWhere('ap.flagStatus = :flagStatus')
                        ->setParameters([
                            'flagDelete' => false,
                            'flagStatus' => true
                        ])
                        ->orderBy('ap.name')
                    ;
                }
            ])

            ->add('flagStatus', ChoiceType::class, [
                'label'     => 'Estado',
                'attr'      => [
                    'class'     => 'form-control'
                ],
                'choices'   => [
                    'ACTIVO' => true,
                    'INACTIVO'  => false,
                ],
            ])
            ->add('flagAccess', CheckboxType::class, [
                'label'     => '¿Tendra acceso al sistema?',
                'required'  => false,
                'attr'      => [
                    'class'     => 'custom-control-input'
                ]
            ])
            #> Botones.
            ->add('save',SubmitType::class,[
                'label' => '<i class="fal fa-save mr-1"></i> Guardar',
                'attr'  => [
                    'class'     => 'btn btn-primary waves-effect waves-themed'
                ],
            ])
            ->add('saveAddOther',SubmitType::class,[
                'label' => '<i class="fal fa-save mr-1"></i> Guardar y Agregar Otro',
                'attr'  => [
                    'class'     => 'btn btn-outline-primary waves-effect waves-themed'
                ],
            ])
            ->add('cancel',ButtonType::class,[
                'label' => '<i class="fal fa-window-close mr-1"></i> Cancelar',
                'attr'  => [
                    'class' => 'cerrar btn btn-outline-secondary waves-effect waves-themed'
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
