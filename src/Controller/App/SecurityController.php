<?php

namespace App\Controller\App;

use App\Entity\App\AppUser;
use App\Services\App\AppTools;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\NotBlank;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/")
 */
class SecurityController extends AbstractController
{
    private $session;
    private $appTools;
    private $phpSpreadsheet;
    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
    }

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('Template/Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/forgotPassword", name="security_forgot_password")
     * @param Request $request
     * @return Response
     */
    public function forgotPassword(MailerInterface $mailer, Request $request): Response
    {
        $form = $this->createFormBuilder(['message' => 'Type your message here'], [
                'attr'      => [
                    'id'        => 'form',
                    'method'    => 'POST',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('email', EmailType::class, [
                'required'  => true,
                'label'     => 'Correo',
                'label_attr' => [
                    'class'         => 'form-label',
                ],
                'attr'      => [
                    'class'         => 'form-control form-control-lg',
                    'placeholder'   => 'Correo',
                    'icon' => 'fal fa-envelope'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Disculpe, Este campo es requerido']),
                ],
            ])
            ->getForm()
        ;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $appUser = $em->getRepository(AppUser::class)->findOneBy([
                'email' => $data['email']
            ]);
            if($appUser){
                $date = new \DateTime();

                $json = json_encode([
                    'id' => $appUser->getId(),
                    'date' => $date->format('Y-m-d H:i:s'),
                ]);
                $token = $this->appTools->encrypt($json);

                $this->appTools->sendMailForgotPassword($mailer,$appUser,$token);

                $this->addFlash('notificactions', 'Se ha enviado un correo de confirmacion');
            } else {
                $this->addFlash('error', 'El correo que esta intentando recuperar se encuentra mal digitado');
            }

            return $this->redirectToRoute('security_login');
        }

        return $this->render('Template/Security/forgotPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/restorePassword/{token}", defaults={"token": null}, name="security_restore_password", methods={"GET","POST"})
     * @param Request $request
     */
    public function restorePassword(MailerInterface $mailer, UserPasswordHasherInterface $encoder, $token): Response
    {
       if($token){
           $decrypt = json_decode($this->appTools->decrypt($token), true);
           if(isset($decrypt['id'])){
               $date = new \DateTime($decrypt['date']);
               $id = $decrypt['id'];
               $dateNow = new \DateTime();
               $diff = $date->diff($dateNow);
               if($diff->i < 15) {
                   $em = $this->getDoctrine()->getManager();
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
                   $this->addFlash('notificactions', 'Se ha enviado un correo con los nuevos datos de acceso');
               } else {
                   $this->addFlash('error', 'Error el tiempo ha expirado, por favor vuelva ha realizar el proceso');
               }
           } else {
               $this->addFlash('error', 'Disculpe usted no tiene acceso a esta parte de la aplicacion');
           }
       } else {
           $this->addFlash('error', 'Disculpe usted no tiene acceso a esta parte de la aplicacion');
       }
        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/logout", name="security_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be executed!
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/viewMail", name="security_view_mail", methods={"GET","POST"})
     * @param Request $request
     */
    public function viewMail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->getParameter('email'), $this->getParameter('emailName')))
            ->to(new Address('munozgeek@gmail.com', 'Daniel Muñoz'))
            ->cc('dmunoz@cloudbase.cl')
            #->addCc('soporte@solutecsystems.com')
            #->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Titulo demo')
            ->htmlTemplate('Template/Email/newUsers.html.twig')
            ->context([
                'appUser' => $this->getUser(),
                'password' => '0408'
            ])

            #->text('Sending emails is fun again!')
            #->html('<p>See Twig integration for better HTML integration!</p>')
        ;

        $mailer->send($email);

        dump('OK');
        exit();
    }

}
