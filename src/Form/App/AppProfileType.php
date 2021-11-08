<?php

namespace App\Form\App;

use App\Entity\App\AppProfile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            #> ficticio
            ->add('profile', HiddenType::class, [
                'mapped' => false
            ])
            #> guardado
            ->add('code', TextType::class, [
                'required'  => true,
                'label'     => 'Código',
                'attr'      => [
                    'class'         => 'form-control border-left-0 bg-transparent pl-0',
                    'placeholder'   => 'Código',
                    'icon' => 'fal fa-code'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'El valor es demasiado largo. Debe tener {{limit}} carácter o menos.'
                    ])
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
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido'])
                ],
            ])
            ->add('description', TextareaType::class, [
                'required'  => true,
                'label'     => 'Descripción',
                'attr'      => [
                    'placeholder'   => 'Descripción',
                    'class' => 'form-control',
                    'rows'           => 5,
                    'icon' => 'fal fa-keyboard'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido'])
                ],
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
            ->add('typeProfile',ChoiceType::class, [
                'required' => true,
                'label' => 'Tipo de perfil',
                'placeholder' => 'Tipo de perfil',
                'attr'          => [
                    'class' => 'form-control'
                ],
                'choices'=> [
                    'ADMINISTRADOR DEL SISTEMA' => 'APP',
                    'USUARIO' => 'USER'
                ],
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
            'data_class' => AppProfile::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['code'],
                    'message' => 'Este valor: {{ value }} ya se esta utilizado. '
                ]),
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
