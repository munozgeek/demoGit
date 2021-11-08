<?php

namespace App\Services\App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PhpSpreadsheet extends AbstractController
{
    public function cellStyle(Worksheet $worksheet, $cell, $type, $aling = false, $colorText = false, $colorBackground = false, $colorBorder = false)
    {
        if($type == 'B' || $type == 'BN'){
            $worksheet->getStyle($cell)->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => $colorBorder],
                    ],
                ],
            ]);
        }

        if ($type == 'N' || $type == 'BN') {
            $worksheet->getStyle($cell)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        if($colorText){
            $worksheet->getStyle($cell)->getFont()->getColor()->setARGB($colorText);
        }

        if($colorBackground){
            $worksheet->getStyle($cell)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($colorBackground)
            ;
        }

        if($aling == 'R') {
            $aling = Alignment::HORIZONTAL_RIGHT;
        }elseif($aling == 'L'){
            $aling = Alignment::HORIZONTAL_LEFT;
        }elseif($aling == 'C'){
            $aling = Alignment::HORIZONTAL_CENTER;
        }elseif($aling == 'J'){
            $aling = Alignment::HORIZONTAL_JUSTIFY;
        } else {
            $aling = Alignment::HORIZONTAL_GENERAL;
        }

        $worksheet->getStyle($cell)->getAlignment()->setWrapText(true);

        $worksheet->getStyle($cell)->applyFromArray([
            'alignment' => [
                'horizontal' => $aling,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    public function dimension(Worksheet $worksheet, $cell, $dimension)
    {
        $worksheet->getColumnDimension($cell)->setWidth($dimension);
    }

    public function cellFormat(Worksheet $worksheet, $cell, $type = false, $isoMoney = false, $decimals = false)
    {
        if($type == 'date'){
            $format = NumberFormat::FORMAT_DATE_DDMMYYYY;
        } elseif ($type == 'number') {
            if($decimals){
                if($decimals == 2){
                    $format = '#,##0.00_-';
                } elseif ($decimals == 3) {
                    $format = '#,##0.000_-';
                } elseif ($decimals == 4) {
                    $format = '#,##0.0000_-';
                } else {
                    $format = '#,##0.00_-';
                }
            } else {
                $format = '#,##0.00_-';
            }
        } elseif ($type == 'money') {
            if($decimals){
                if($decimals == 2){
                    $format = '_("'.$isoMoney.'"* #,##0.00_);_("'.$isoMoney.'"* \(#,##0.00\);_("'.$isoMoney.'"* "-"??_);_(@_)';
                } elseif ($decimals == 3) {
                    $format = '_("'.$isoMoney.'"* #,##0.000_);_("'.$isoMoney.'"* \(#,##0.000\);_("'.$isoMoney.'"* "-"??_);_(@_)';
                } elseif ($decimals == 4) {
                    $format = '_("'.$isoMoney.'"* #,##0.0000_);_("'.$isoMoney.'"* \(#,##0.0000\);_("'.$isoMoney.'"* "-"??_);_(@_)';
                } else {
                    $format = '_("'.$isoMoney.'"* #,##0.00_);_("'.$isoMoney.'"* \(#,##0.00\);_("'.$isoMoney.'"* "-"??_);_(@_)';
                }
            } else {
                $format = '_("'.$isoMoney.'"* #,##0.00_);_("'.$isoMoney.'"* \(#,##0.00\);_("'.$isoMoney.'"* "-"??_);_(@_)';
            }
        } else {
            $format = NumberFormat::FORMAT_TEXT;
        }

        $worksheet->getStyle($cell)->getNumberFormat()->setFormatCode($format)
        ;
    }

    public function cellAddImage(Worksheet $worksheet, $cell, $image, array $dimension,  $targetDirectory, $rotate = false)
    {

        $drawing = new Drawing();
        $drawing->setName($image);
        $drawing->setPath($targetDirectory.'/'.$image);
        $drawing->setCoordinates($cell);
        $drawing->setOffsetY(60);
        $drawing->setOffsetX(10);
        if(isset($dimension['width']) && isset($dimension['height'])){
            $drawing->setWidthAndHeight($dimension['width'], $dimension['height']);
        } elseif (isset($dimension['width'])){
            $drawing->setWidth($dimension['width']);
        } elseif (isset($dimension['height'])){
            $drawing->setHeight($dimension['height']);
        }
        if($rotate){
            $drawing->setRotation($rotate);
        }
        $drawing->setWorksheet($worksheet);
    }

    public function cell($index)
    {
        $column[] = 'A';
        $column[] = 'B';
        $column[] = 'C';
        $column[] = 'D';
        $column[] = 'E';
        $column[] = 'F';
        $column[] = 'G';
        $column[] = 'H';
        $column[] = 'I';
        $column[] = 'J';
        $column[] = 'K';
        $column[] = 'L';
        $column[] = 'M';
        $column[] = 'N';
        $column[] = 'O';
        $column[] = 'P';
        $column[] = 'Q';
        $column[] = 'R';
        $column[] = 'S';
        $column[] = 'T';
        $column[] = 'U';
        $column[] = 'V';
        $column[] = 'W';
        $column[] = 'X';
        $column[] = 'Y';
        $column[] = 'Z';
        $column[] = 'AA';
        $column[] = 'AB';
        $column[] = 'AC';
        $column[] = 'AD';
        $column[] = 'AE';
        $column[] = 'AF';
        $column[] = 'AG';
        $column[] = 'AH';
        $column[] = 'AI';
        $column[] = 'AJ';
        $column[] = 'AK';
        $column[] = 'AL';
        $column[] = 'AM';
        $column[] = 'AN';
        $column[] = 'AO';
        $column[] = 'AP';
        $column[] = 'AQ';
        $column[] = 'AR';
        $column[] = 'AS';
        $column[] = 'AT';
        $column[] = 'AU';
        $column[] = 'AV';
        $column[] = 'AW';
        $column[] = 'AX';
        $column[] = 'AY';
        $column[] = 'AZ';
        return $column[$index];
    }

}
