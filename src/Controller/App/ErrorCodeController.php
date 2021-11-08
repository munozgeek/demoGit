<?php

namespace App\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;


class ErrorCodeController extends AbstractController
{

    public function show(FlattenException $exception, DebugLoggerInterface $logger = null): Response
    {
        $code = $exception->getStatusCode();

        if($code === 404){
            return $this->render('Template/Error/error404.html.twig');
        }elseif($code === 500){
            return $this->render('Template/Error/error500.html.twig');
        }

        return $this->render('Template/Error/error.html.twig', [
        ]);
    }
}
