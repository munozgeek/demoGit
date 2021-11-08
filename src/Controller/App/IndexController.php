<?php

namespace App\Controller\App;

use App\Entity\App\AppMenu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_v1")
     */
    public function index(): Response
    {
        return $this->render('App/AppDashboard/index.html.twig', [
        ]);
    }
}
