<?php

namespace App\Controller\App;

use App\Entity\App\AppMenu;
use App\Entity\App\AppProfile;
use App\Entity\App\AppProfileOrder;
use App\Form\App\AppProfileType;
use App\Services\App\AppTools;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Column\BoolColumn;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
#> Datatables
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TwigColumn;

/**
 * @Route("/_profile_user")
 */
class AppProfileController extends AbstractController
{
    public $appTools;
    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
        $session = new Session();
        $session->set('edit', $appTools->isGranted('ROLE_ADMPROFILE-M'));
        $session->set('delete', $appTools->isGranted('ROLE_ADMPROFILE-E'));
    }

    /**
     * @Route("/", name="app_profile_index", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMPROFILE')")
     */
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('check', TwigColumn::class, [
                'className' => 'checkBox width-1',
                'label' => '',
                'template' => 'Template/DataTable/check.html.twig',
            ])

            ->add('code', TextColumn::class, [
                'field' => 'ap.code',
                'label' => 'CÓDIGO',
                'className' => 'bold width-10'
            ])
            ->add('name', TextColumn::class, [
                'field' => 'ap.name',
                'label' => 'NOMBRE',
                'className' => 'bold'
            ])
            ->add('description', TextColumn::class, [
                'field' => 'ap.name',
                'label' => 'NOMBRE',
                'className' => 'bold'
            ])
            ->add('typeProfile', TextColumn::class, [
                'field' => 'ap.typeProfile',
                'label' => 'TIPO DE PERFIL',
                'className' => 'bold',
                'render' => function($value) {
                    if($value == 'APP'){
                        $typeProfile = 'SUPER ADMINISTRADOR';
                    } elseif ($value == 'USER') {
                        $typeProfile = 'USUARIO';
                    } else {
                        $typeProfile = 'SIN DEFINIR';
                    }
                    return $typeProfile;
                }
            ])
            ->add('flagStatus', BoolColumn::class, [
                'field' => 'ap.flagStatus',
                'label' => 'ESTADO',
                'className' => 'bold width-10',
                'trueValue' => 'ACTIVO',
                'falseValue' => 'INACTIVO',
                'nullValue' => 'SIN DEFINIR'
            ])
            ->add('buttons', TwigColumn::class, [
                'className' => 'buttons width-10',
                'label' => 'ACCIÓN',
                'template' => 'Template/DataTable/buttons.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => AppProfile::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('ap')
                        ->from(AppProfile::class, 'ap')
                        ->where('ap.flagDelete = :flagDelete')
                        ->setParameter('flagDelete', false)
                    ;
                },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('App/AppProfile/index.html.twig', [
            'datatable' => $table
        ]);
    }
    
    private function newAppProfileOrder($em, $appProfile, $order, $appMenuModule, $appMenuSubModule = null, $appMenuSubModule2 = null, $appMenuSubModule3 = null)
    {
        $appDynamicMenu = new AppProfileOrder();
        $appDynamicMenu
            ->setAppProfile($appProfile)
            ->setAppMenuModule($appMenuModule)
            ->setAppMenuSubModule($appMenuSubModule)
            ->setAppMenuSubModule2($appMenuSubModule2)
            ->setAppMenuSubModule3($appMenuSubModule3)
            ->setOrderProfile($order)
        ;
        $em->persist($appDynamicMenu);
        $em->flush();
    }

    private function newDetailProfile($em, $appProfile, $menu)
    {
        $nModulo=1;
        foreach ($menu AS $i){
            $appMenuModulo = $em->getRepository(AppMenu::class)->find($i['id']);
            $appProfile->addAppMenu($appMenuModulo);
            $this->newAppProfileOrder($em, $appProfile, $nModulo, $appMenuModulo);
            if(isset($i['children'])){
                $nSubModulo=1;
                foreach ($i['children'] AS $ii){
                    $appMenuSubModulo = $em->getRepository(AppMenu::class)->find($ii['id']);
                    $appProfile->addAppMenu($appMenuSubModulo);
                    $this->newAppProfileOrder($em, $appProfile, $nSubModulo, $appMenuModulo, $appMenuSubModulo);
                    if(isset($ii['children'])){
                        $nSubModulo2=1;
                        foreach ($ii['children'] AS $iii){
                            $appMenuSubModulo2 = $em->getRepository(AppMenu::class)->find($iii['id']);
                            $appProfile->addAppMenu($appMenuSubModulo2);
                            $this->newAppProfileOrder($em, $appProfile, $nSubModulo2, $appMenuModulo, $appMenuSubModulo, $appMenuSubModulo2);
                            if(isset($iii['children'])){
                                $nSubModulo3=1;
                                foreach ($iii['children'] AS $iv){
                                    $appMenuSubModulo3 = $em->getRepository(AppMenu::class)->find($iv['id']);
                                    $appProfile->addAppMenu($appMenuSubModulo3);
                                    $this->newAppProfileOrder($em, $appProfile, $nSubModulo3, $appMenuModulo, $appMenuSubModulo, $appMenuSubModulo2, $appMenuSubModulo3);
                                    $nSubModulo3++;
                                }
                            }
                            $nSubModulo2++;
                        }
                    }
                    $nSubModulo++;
                }
            }
            $nModulo++;
        }
        $em->persist($appProfile);
        $em->flush();
    }

    /**
     * @Route("/new", name="app_profile_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMPROFILE-N')")
     */
    public function new(Request $request): Response
    {
        $appProfile = new AppProfile();
        $form = $this->createForm(AppProfileType::class, $appProfile, [
            'attr'      => [
                'id'        => 'form',
                'method'    => 'POST',
                'autocomplete' => 'off',
            ]
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            #> Request
            $profileMenu= json_decode($form['profile']->getData(), true);
            $actionMenu = $request->get('actionMenu',[]);

            $em = $this->getDoctrine()->getManager();
            $appProfile->setFlagDelete(false);

            foreach ($actionMenu AS $i){
                $appMenu = $em->getRepository(AppMenu::class)->find($i);
                $appProfile->addAppMenu($appMenu);
            }

            $em->persist($appProfile);
            $em->flush();

            $this->newDetailProfile($em, $appProfile, $profileMenu);


            $this->addFlash('new', 'El Registro ha sido creado exitosamente.');

            $redireccion = $form->get('save')->isClicked()
                ? $this->redirectToRoute('app_profile_index')
                : $this->redirect($this->generateUrl('app_profile_new'));
            return $redireccion;
        }

        $allMenu = $this->getDoctrine()->getRepository(AppMenu::class)->allMenu();

        return $this->render('App/AppProfile/form.html.twig', [
            'form' => $form->createView(),
            'allMenu' => $allMenu['menu'],
            'allAction' => $allMenu['action'],
            'is_new' => true
        ]);
    }

    /**
     * @Route("/edit/{id}/{token}", defaults={"id": null,"token": null}, name="app_profile_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMPROFILE-M')")
     */
    public function edit(Request $request, AppProfile $appProfile, $token)#: Response
    {
        if ($this->isCsrfTokenValid('edit'.$appProfile->getId(), $token)) {

            $appMenu = new ArrayCollection();
            foreach ($appProfile->getAppMenu() AS $i){
                $appMenu->add($i);
            }

            $form = $this->createForm(AppProfileType::class, $appProfile, [
                'attr'      => [
                    'id'        => 'form',
                    'method'    => 'POST',
                    'autocomplete' => 'off',
                ]
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                #> Request
                $profileMenu= json_decode($form['profile']->getData(), true);
                $actionMenu = $request->get('actionMenu',[]);

                $em = $this->getDoctrine()->getManager();

                foreach ($appMenu AS $i) {
                    $appProfile->removeAppMenu($i);
                }
                $em->persist($appProfile);
                $em->flush();

                foreach ($actionMenu AS $i){
                    $appMenu = $em->getRepository(AppMenu::class)->find($i);
                    $appProfile->addAppMenu($appMenu);
                }
                $em->persist($appProfile);

                $appDynamicMenu = $em->getRepository(AppProfileOrder::class)->profileDetail($appProfile);
                foreach ($appDynamicMenu AS $i){
                    $em->remove($i);
                }
                $em->flush();

                $this->newDetailProfile($em, $appProfile, $profileMenu);


                $this->addFlash('edit', 'El Registro ha sido actualizado exitosamente.');

                return $this->redirectToRoute('app_profile_index');
            }

            $allMenu = $this->getDoctrine()->getRepository(AppMenu::class)->allMenu();
            $profileOrder = $this->getDoctrine()->getRepository(AppProfileOrder::class)->menuUser($appProfile);

            $actionSelected = [];
            foreach ($appProfile->getAppMenu() AS $i){
                $actionSelected[] = $i->getId();
            }

            return $this->render('App/AppProfile/form.html.twig', [
                'form' => $form->createView(),
                'allMenu' => $allMenu['menu'],
                'allAction' => $allMenu['action'],
                'profileOrder' => $profileOrder,
                'actionSelected' => $actionSelected,
                'is_new' => true
            ]);
        } else {
            return $this->render('Template/Error/errorToken.html.twig', [
                'url' => $this->generateUrl('app_profile_index')
            ]);
        }
    }

    /**
     * @Route("/{id}/{token}", defaults={"id": null,"token": null}, name="app_profile_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMPROFILE-E')")
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
                    $appProfile = $em->getRepository(AppProfile::class)->find($i['id']);
                    $em->remove($appProfile);
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
                $appProfile = $em->getRepository(AppProfile::class)->find($id);
                $em->remove($appProfile);
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

}
