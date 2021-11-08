<?php

namespace App\Services\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use \TCPDF;

class AppPdf extends AbstractController
{
    public $appTools;
    public function __construct(AppTools $appTools)
    {
        $this->appTools = $appTools;
    }

    public function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public function mount($mount)
    {
        if ($mount == 'January') {
            $mount = "Enero";
        } elseif ($mount == 'February') {
            $mount = "Febrero";
        } elseif ($mount == 'March') {
            $mount = "Marzo";
        } elseif ($mount == 'April') {
            $mount = "Abril";
        } elseif ($mount == 'May') {
            $mount = "Mayo";
        } elseif ($mount == 'June') {
            $mount = "Junio";
        } elseif ($mount == 'July') {
            $mount = "Julio";
        } elseif ($mount == 'August') {
            $mount = "Agosto";
        } elseif ($mount == 'September') {
            $mount = "Septiembre";
        } elseif ($mount == 'October') {
            $mount = "Octubre";
        } elseif ($mount == 'November') {
            $mount = "Noviembre";
        } elseif ($mount == 'December') {
            $mount = "Diciembre";
        }
        return $mount;
    }

}
