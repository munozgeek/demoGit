<?php
namespace App\Security;

use App\Entity\App\AppProfileOrder;
use App\Entity\App\AppUser;
use App\Services\App\AppTools;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private const LOGIN_ROUTE = 'security_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $appTools;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, AppTools $appTools)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->appTools = $appTools;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
            'ip' => $request->getClientIp(),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $appUser = $this->entityManager->getRepository(AppUser::class)->loadUserByUsername($credentials['email']);
        if (!$appUser) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Disculpa pero su correo no existe en nuestro sistema.');
        } else {
            $isActive = $appUser->getFlagAccess();
            if(!$isActive){
                throw new CustomUserMessageAuthenticationException('Su usuario no tiene acceso.');
            }
            if(!$appUser->getAppProfile()){
                throw new CustomUserMessageAuthenticationException('User not profile.');
            }
        }

        $session = new Session();
        $appProfile = $this->entityManager->getRepository(AppProfileOrder::class)->menuUser($appUser->getAppProfile());
        $session->set('appProfileOrder', $appProfile);
        $session->set('methodLogin', 'normal');

        if($appUser->getLastConnectionDate()){
            $lastConnectionDateD = $appUser->getLastConnectionDate()->format('d/m/Y');
            $lastConnectionDateH = $appUser->getLastConnectionDate()->format('h:i:s a');
        } else {
            $lastConnectionDateD = null;
            $lastConnectionDateH = null;
        }
        $appUser
            ->setIp($credentials['ip'])
        ;
        $this->entityManager->persist($appUser);
        $this->entityManager->flush();

        $session->set('lastConnectionDate', [
            'date' => $lastConnectionDateD,
            'hour' => $lastConnectionDateH,
        ]);

        #> super Administrador
        return $appUser;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        } elseif ($request->get('_target_path')){
            return new RedirectResponse($this->urlGenerator->generate($request->get('_target_path')));
        } else {
            return new RedirectResponse($this->urlGenerator->generate($request->get('_target_path')));
        }
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function getPassword($credentials): ?string
    {
        // TODO: Implement getPassword() method.
        //dump($credentials);
        return  '';
    }
}