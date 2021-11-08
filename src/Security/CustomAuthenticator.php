<?php
namespace App\Security;

use App\Entity\App\AppProfileOrder;
use App\Repository\App\AppUserRepository;
use App\Services\App\AppTools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

// ...

class CustomAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;
    private const LOGIN_ROUTE = 'security_login';
    private $userRepository;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $appTools;
    private $flashBag;

    public function __construct(
        AppUserRepository $userRepository, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager, AppTools $appTools, FlashBagInterface $flashBag)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->appTools = $appTools;
        $this->flashBag = $flashBag;
    }

    public function supports(Request $request): ?bool
    {
        // TODO: Implement supports() method.
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $email  = $request->request->get('email');
        $appUser= $this->userRepository->loadUserByUsername($email);

        $session = $request->getSession();
        $appProfile = $this->entityManager->getRepository(AppProfileOrder::class)->menuUser($appUser->getAppProfile());
        $session->set('appProfileOrder', $appProfile);

        if($appUser->getLastConnectionDate()){
            $lastConnectionDateD = $appUser->getLastConnectionDate()->format('d/m/Y');
            $lastConnectionDateH = $appUser->getLastConnectionDate()->format('h:i:s a');
        } else {
            $lastConnectionDateD = null;
            $lastConnectionDateH = null;
        }
        $appUser
            ->setIp($request->getClientIp())
        ;
        $this->entityManager->persist($appUser);
        $this->entityManager->flush();

        $session->set('lastConnectionDate', [
            'date' => $lastConnectionDateD,
            'hour' => $lastConnectionDateH,
        ]);

        // TODO: Implement onAuthenticationSuccess() method.
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        } elseif ($request->get('_target_path')){
            return new RedirectResponse($this->urlGenerator->generate($request->get('_target_path')));
        } else {
            return new RedirectResponse($this->urlGenerator->generate($request->get('_target_path')));
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // TODO: Implement onAuthenticationFailure() method.
        if($exception->getMessage() == 'inactiveUser'){
            $message = 'Su usuario se encuentra inactivo.';
        } elseif($exception->getMessage() == 'notAccessUser') {
            $message = 'Su usuario no tiene acceso.';
        } else {
            $message = 'Usuario Y/o contraseña erroneos.';
        }
        $this->flashBag->add('error', $message);
        return new RedirectResponse($this->urlGenerator->generate('security_login'));
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email = $request->request->get('email');

        $validateUser = $this->userRepository->loadUserByUsername($email);
        if(!$validateUser){
            throw new CustomUserMessageAuthenticationException('noUser');
        } elseif(!$validateUser->getFlagStatus()){
            throw new CustomUserMessageAuthenticationException('inactiveUser');
        } elseif(!$validateUser->getFlagAccess()){
            throw new CustomUserMessageAuthenticationException('notAccessUser');
        }
        $password = $request->request->get('password');
        $csrfToken = $request->request->get('_csrf_token');
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [new CsrfTokenBadge('authenticate', $csrfToken)]
        );
    }
}