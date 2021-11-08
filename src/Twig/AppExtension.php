<?php
namespace App\Twig;

use App\Entity\App\AppProfileOrder;
use App\Services\App\AppTools;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $appTools;

    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('encrypt', [$this, 'appEncrypt']),
            new TwigFilter('stripslashes', [$this, 'stripslashes']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('parameter', [$this, 'appParameter']),
            new TwigFunction('getDataEntity', [$this, 'getDataEntityTiwg']),
            new TwigFunction('getDataEntityParamArray', [$this, 'getDataEntityParamArrayTiwg']),
            new TwigFunction('getMenuUser', [$this, 'getMenuUser']),
            new TwigFunction('urlToLogin', [$this, 'urlToLogin']),
        ];
    }

    public function appEncrypt($valor)
    {
        return $this->appTools->encrypt($valor);
    }

    public function urlToLogin()
    {
        $typeLogin = $this->appTools->requestStack()->getSession()->get('methodLogin');
        $url = $this->appTools->urlToLogin();
        if($typeLogin === 'auth0'){
            $url = $this->appTools->urlToLogin(true);
        }
        return $url;
    }

    public function stripslashes($valor)
    {
        return stripslashes($valor);
    }

    public function appParameter($valor)
    {
        return $this->appTools->parameter($valor);
    }

    public function getDataEntityTiwg($entity, $id)
    {
        return $this->appTools->getDataEntityTiwg($entity, $id);
    }

    public function getDataEntityParamArrayTiwg($entity, $array)
    {
        return $this->appTools->getDataEntityParamArrayTiwg($entity, $array);
    }

    public function getMenuUser()
    {
        if($this->appTools->myUser()) {
            $currentUser = $this->appTools->myUser();
            $session = new Session();
            if (empty($session->get('appProfileOrder')) && $currentUser) {
                $appProfile = $this->appTools->doctrineManager()->getRepository(AppProfileOrder::class)->menuUser($currentUser->getAppProfile());
                $session->set('appProfileOrder', $appProfile);
                $menu = $appProfile;
            } elseif (!empty($session->get('appProfileOrder'))) {
                $menu = $session->get('appProfileOrder');
            } else {
                $menu = [];
            }
        } else {
            $menu = [];
        }

        return $menu;
    }
}
