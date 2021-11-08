<?php

namespace App\Security;

use App\Entity\App\AppProfileOrder;
use App\Entity\App\AppUser;
use App\Services\App\AppTools;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class OAuthProvider extends OAuthUserProvider
{
    private $appTools;
    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response):AppUser
    {
        $em = $this->appTools->doctrineManager();

        $source = $response->getResourceOwner()->getName();
        $email = null;

        if ($source === 'auth0') {
            $data = $response->getData();
            $email = $data['email'];
        }

        $appUser = $em->getRepository(AppUser::class)->loadUserByUsername($email);

        $urlLogout = $this->appTools->urlToLogin(true);

        $button = "<a href='{$urlLogout}' class='btn btn-sm btn-light-primary font-weight-bolder py-2 px-5'><i class='fad fa-sign-out-alt mr-2'></i>Cerrar la sesión</a>";
        if (!$appUser) {
            throw new CustomUserMessageAuthenticationException('Disculpa pero su cuenta no existe en nuestro sistema. '.$button);
        }
        if(!$appUser->getFlagStatus()){
            throw new CustomUserMessageAuthenticationException('Su usuario se encuentra inactiva.'.$button);
        }elseif(!$appUser->getFlagStatus()){
            throw new CustomUserMessageAuthenticationException('Su usuario no tiene acceso.'.$button);
        }


        $session = new Session();
        $appProfile = $em->getRepository(AppProfileOrder::class)->menuUser($appUser->getAppProfile());
        $session->set('appProfileOrder', $appProfile);
        $session->set('methodLogin', $source);

        if($appUser->getLastConnectionDate()){
            $lastConnectionDateD = $appUser->getLastConnectionDate()->format('d/m/Y');
            $lastConnectionDateH = $appUser->getLastConnectionDate()->format('h:i:s a');
        } else {
            $lastConnectionDateD = null;
            $lastConnectionDateH = null;
        }
        $appUser
            ->setIp($this->appTools->requestStack()->getClientIp())
        ;
        $em->persist($appUser);
        $em->flush();

        $session->set('lastConnectionDate', [
            'date' => $lastConnectionDateD,
            'hour' => $lastConnectionDateH,
        ]);

        return $appUser;
    }
}