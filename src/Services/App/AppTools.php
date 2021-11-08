<?php

namespace App\Services\App;

use App\Entity\App\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;

class AppTools extends AbstractController
{
    private $key;
    private $requestStack;
    private $session;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->key          = 'APP_ADMIN_V3_SYMFONY5';
        $this->requestStack = $requestStack->getCurrentRequest();
        $this->session      = new Session();
        $this->entityManager= $entityManager;
    }

    public function myUser()
    {
        return $this->getUser();
    }
    public function pathToUrl($path)
    {
        return $this->generateUrl($path);
    }

    public function parameter($value)
    {
        return $this->getParameter($value);
    }

    public function encrypt($input)
    {
        $output=false;
        for ($i=0;$i<2;$i++){
            if($i==0){
                $output = base64_encode($input);
            }else{
                $output = base64_encode($output);
            }
        }
        return $output;
    }

    public function decrypt($input)
    {
        $output=false;
        for ($i=0;$i<2;$i++){
            if($i==0){
                $output = base64_decode($input);
            }else{
                $output = base64_decode($output);
            }
        }
        return $output;
    }

    public function urlRetun($url,$hash = false)
    {
        if($hash){
            $hash = $this->generateUrl($url,['hash'=>uniqid()]);
        } else {
            $hash = $this->generateUrl($url);
        }

        return '#'.$hash;
    }

    public function setUrl($url): array
    {
        return [
            'action'    => $url,
            'attr'      => [
                'class'     => 'smart-form',
                'id'        => 'formAjax',
                'method'    => 'POST',
                'autocomplete' => 'off',
            ]
        ];
    }

    public function doctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    public function requestStack()
    {
        return $this->requestStack;
    }

    public function getDataEntityTiwg($entity, $id)
    {
        return $this->entityManager->getRepository($entity)->find($id);
    }

    public function getDataEntityParamArrayTiwg($entity, $array)
    {
        return $this->entityManager->getRepository($entity)->findBy($array);
    }

    public function sendMail(MailerInterface $mailer, AppUser $appUser, $password, $changePassword = false)
    {
        if($changePassword){
            $title = 'Su cuenta ha sido restablecida.';
            $action = 'Restablecida cuenta de usuario';
        } else {
            $title = 'Su cuenta ha sido creada.';
            $action = 'Nueva cuenta de usuario';
        }
        $email = (new TemplatedEmail())
            ->from(new Address($this->getParameter('email'), $this->getParameter('emailName')))
            ->to(new Address($appUser->getEmail(), $appUser->getName().' '.$appUser->getSurname()))
            #->cc('dmunoz@cloudbase.cl')
            #->addCc('soporte@solutecsystems.com')
            #->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject($title)
            ->htmlTemplate('Template/Email/newUsers.html.twig')
            ->context([
                'changePassword' => $changePassword,
                'action' => $action,
                'appUser' => $appUser,
                'password' => $password
            ])

            #->text('Sending emails is fun again!')
            #->html('<p>See Twig integration for better HTML integration!</p>')
        ;

        $mailer->send($email);
    }

    public function sendMailForgotPassword(MailerInterface $mailer, AppUser $appUser, $token)
    {

        $email = (new TemplatedEmail())
            ->from(new Address($this->getParameter('email'), $this->getParameter('emailName')))
            ->to(new Address($appUser->getEmail(), $appUser->getName().' '.$appUser->getSurname()))
            #->cc('dmunoz@cloudbase.cl')
            #->addCc('soporte@solutecsystems.com')
            #->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Solicitud para restablecer su contraseña')
            ->htmlTemplate('Template/Email/forgotPassword.html.twig')
            ->context([
                'action' => 'Solicitud de cambio de contraseña',
                'appUser' => $appUser,
                'token' => $token
            ])
        ;

        $mailer->send($email);
    }

    public function urlToLogin($auth0 = false)
    {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        $url = $protocol.'://'.$this->requestStack()->getHttpHost().$this->generateUrl('security_logout');
        if($auth0){
            $url = $this->getParameter('base_url').'/v2/logout?returnTo='.$url.'&client_id='.$this->getParameter('client_id');
        }
        return $url;
    }
}
