<?php
namespace App\Controller\App;

use App\Entity\App\AppUser;
use App\Form\App\AppProfileUserType;
use App\Services\App\AppTools;
use App\Services\App\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AppProfileUserController extends AbstractController
{
    public $appTools;
    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
    }
    /**
     * @Route("/profile", name="app_profile_user", methods={"GET","POST"})
     */
    public function profile(Request $request, UserPasswordHasherInterface $encoder, FileUploader $fileUploader): Response
    {
        $appUser = $this->getDoctrine()->getRepository(AppUser::class)->find($this->getUser()->getId());
        $imageUser = $appUser->getImage();
        $form = $this->createForm(AppProfileUserType::class, $appUser, [
            'attr'      => [
                'id'        => 'form',
                'method'    => 'POST',
                'autocomplete' => 'off',
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $fileImage = $form['image']->getData();
            if ($fileImage) {
                $fileName = $fileUploader->upload($fileImage);
                $appUser->setImage($fileName);
            } else {
                $appUser->setImage($imageUser);
            }

            $pass = $form['pass']->getData();
            if($pass){
                $appUser->setSalt(md5(time()));
                $encoded = $encoder->hashPassword($appUser, $pass);
                $appUser->setPassword($encoded);
            }

            $entityManager->persist($appUser);
            $entityManager->flush();

            $this->addFlash('new', 'Modificacion realizada exitosamente.');
            return $this->redirectToRoute('app_profile_user');
        }
        return $this->render('App/AppProfileUser/index.html.twig', [
            'appUser' => $appUser,
            'form' => $form->createView(),
        ]);
    }
}