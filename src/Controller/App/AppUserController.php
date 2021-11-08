<?php

namespace App\Controller\App;

use App\Entity\App\AppUser;
use App\Form\App\AppUserType;
use App\Services\App\AppTools;
use App\Services\App\FileUploader;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
#> Datatables

use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TwigColumn;


/**
 * @Route("/user")
 */
class AppUserController extends AbstractController
{
    public $appTools;
    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
        $session = new Session();
        $session->set('edit', $appTools->isGranted('ROLE_ADMUSER-M'));
        $session->set('email', $appTools->isGranted('ROLE_ADMUSER-M'));
        $session->set('delete', $appTools->isGranted('ROLE_ADMUSER-E'));
    }

    /**
     * @Route("/", name="app_user_index", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMUSER')")
     */
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('check', TwigColumn::class, [
                'className' => 'checkBox  -check sorting_1',
                'label' => '',
                'template' => 'Template/DataTable/check.html.twig',
            ])
            ->add('image', TwigColumn::class, [
                'className' => 'with-img',
                'label' => 'Imagen',
                'template' => 'Template/DataTable/imageUser.html.twig',
            ])

            ->add('name', TextColumn::class, [
                'field' => 'ap.name',
                'label' => 'NOMBRE',
                'className' => ''
            ])
            ->add('surname', TextColumn::class, [
                'field' => 'ap.surname',
                'label' => 'APELLIDO',
                'className' => ''
            ])
            ->add('userName', TextColumn::class, [
                'field' => 'ap.username',
                'label' => 'NOMBRE DE USUARIO',
                'className' => ''
            ])
            ->add('email', TextColumn::class, [
                'field' => 'ap.email',
                'label' => 'CORREO',
                'className' => ''
            ])
            ->add('flagStatus', BoolColumn::class, [
                'field' => 'ap.flagStatus',
                'label' => 'ESTADO',
                'className' => ' tb-col-md',
                'trueValue' => 'ACTIVO',
                'falseValue' => 'INACTIVO',
                'nullValue' => 'SIN DEFINIR'
            ])
            ->add('buttons', TwigColumn::class, [
                'className' => ' buttons',
                'label' => 'ACCIÓN',
                'template' => 'Template/DataTable/buttonsUser.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => AppUser::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('ap')
                        ->from(AppUser::class, 'ap')
                    ;
                },
            ])
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('App/AppUser/index.html.twig', [
            'datatable' => $table
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMUSER-N')")
     */
    public function new(Request $request, MailerInterface $mailer, UserPasswordHasherInterface $encoder, FileUploader $fileUploader): Response
    {
        $appUser = new AppUser();
        $form = $this->createForm(AppUserType::class, $appUser, [
            'attr'      => [
                'id'        => 'form',
                'method'    => 'POST',
                'autocomplete' => 'off',
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $fileImage = $form['image']->getData();
            if ($fileImage) {
                $fileName = $fileUploader->upload($fileImage);
                $appUser->setImage($fileName);
            }
            $appUser->setSalt(md5(time()));
            $password = substr($appUser->getSalt(), 0, 5);
            $encoded = $encoder->hashPassword($appUser, $password);
            $appUser->setPassword($encoded);
            $appUser
                ->setCreationDate(new \DateTime())
            ;
            $em->persist($appUser);
            $em->flush();
            $this->appTools->sendMail($mailer, $appUser, $password);

            $this->addFlash('new', 'El Registro ha sido creado exitosamente.');

            $redireccion = $form->get('save')->isClicked()
                ? $this->redirectToRoute('app_user_index')
                : $this->redirect($this->generateUrl('app_user_new'));
            return $redireccion;
        }

        return $this->render('App/AppUser/form.html.twig', [
            'appUser' => $appUser,
            'form' => $form->createView(),
            'is_new' => true
        ]);
    }

    /**
     * @Route("/edit/{id}/{token}", defaults={"id": null,"token": null}, name="app_user_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMUSER-M')")
     */
    public function edit(Request $request, AppUser $appUser, $token, FileUploader $fileUploader): Response
    {
        if ($this->isCsrfTokenValid('edit'.$appUser->getId(), $token)) {
            $imageUser = $appUser->getImage();
            $form = $this->createForm(AppUserType::class, $appUser, [
                'attr'      => [
                    'id'        => 'form',
                    'method'    => 'POST',
                    'autocomplete' => 'off',
                ]
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $fileImage = $form['image']->getData();
                if ($fileImage) {
                    $fileName = $fileUploader->upload($fileImage);
                    $appUser->setImage($fileName);
                } else {
                    $appUser->setImage($imageUser);
                }

                $em->persist($appUser);
                $em->flush();

                $this->addFlash('edit', 'El Registro ha sido actualizado exitosamente.');

                return $this->redirectToRoute('app_user_index');
            }

            return $this->render('App/AppUser/form.html.twig', [
                'appUser' => $appUser,
                'form' => $form->createView(),
                'is_new' => false
            ]);
        } else {
            return $this->render('Template/Error/errorToken.html.twig', [
                'url' => $this->generateUrl('app_user_index'),
            ]);
        }
    }

    /**
     * @Route("/{id}/{token}", defaults={"id": null,"token": null}, name="app_user_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMUSER-E')")
     */
    public function delete($id, $token): Response
    {
        $em = $this->getDoctrine()->getManager();
        $params = json_decode($id, true);
        $json = [
            'status' => 'error',
            'title'  => 'Error',
            'message'=> 'Disculpe pero no se reconoció ningún registro para eliminar',
            'errorCode' => 500
        ];

        if(is_array($params)){
            if ($this->isCsrfTokenValid('edit-All', $token)) {
                foreach ($params AS $i){
                    $appUser = $em->getRepository(AppUser::class)->find($i['id']);
                    $em->remove($appUser);
                    $em->flush();
                }
                $json = [
                    'status'    => 'ok',
                    'title'     => 'Eliminado.',
                    'message'   => 'Se han eliminado los registros satisfactoriamente',
                    'errorCode' => 200
                ];
            }
        } else {
            if ($this->isCsrfTokenValid('delete'.$id, $token)) {
                $appUser = $em->getRepository(AppUser::class)->find($id);
                $em->remove($appUser);
                $em->flush();
                $json = [
                    'status'    => 'ok',
                    'title'     => 'Eliminado.',
                    'message'   => 'El registro se ah eliminado satisfactoriamente',
                    'errorCode' => 200
                ];
            }
        }
       return new JsonResponse($json, $json['errorCode']);
    }

    /**
     * @Route("/restorePassword/{id}/{token}", defaults={"id": null,"token": null}, name="app_user_send_mail", methods={"POST"})
     */
    public function restorePassword($id, $token, MailerInterface $mailer, UserPasswordHasherInterface $encoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $json = [
            'status' => 'error',
            'title'  => 'Error',
            'message'=> 'Disculpe pero no se reconoció ningún registro para eliminar',
            'errorCode' => 500
        ];

        if ($this->isCsrfTokenValid('mail'.$id, $token)) {
            $appUser = $em->getRepository(AppUser::class)->find($id);

            $appUser->setSalt(md5(time()));
            $password = substr($appUser->getSalt(), 0, 5);
            $encoded = $encoder->hashPassword($appUser, $password);
            $appUser
                ->setPassword($encoded)
            ;
            $em->persist($appUser);
            $em->flush();
            $this->appTools->sendMail($mailer, $appUser, $password, true);
            $json = [
                'status'    => 'ok',
                'title'     => '¡Correo Enviado!',
                'message'   => 'Se ha enviado los nuevos datos de acceso al usuario.',
                'errorCode' => 200
            ];
        }

       return new JsonResponse($json, $json['errorCode']);
    }

}
