<?php
/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Salamek\Zasilkovna;


use Salamek\Zasilkovna\Enum\LabelDecomposition;
use Salamek\Zasilkovna\Enum\LabelPosition;
use Salamek\Zasilkovna\Exception\WrongDataException;
use Salamek\Zasilkovna\Model\PacketAttributes;


class Label
{
    /**
     * @param PacketAttributes[] $packetAttributes
     * @param int $decomposition
     * @return string
     * @throws \Exception
     */
    public static function generateLabels(IApi $api, array $packetAttributes, $decomposition = LabelDecomposition::FULL)
    {
        if (!in_array($decomposition, LabelDecomposition::$list)) {
            throw new WrongDataException(sprintf('unknown $decomposition only %s are allowed', implode(', ', LabelDecomposition::$list)));
        }

        $packageNumbers = [];

        /** @var PacketAttributes $packetAttribute */
        foreach ($packetAttributes AS $packetAttribute) {
            $packageNumbers[] = $packetAttribute->getId();
        }

        $pdf = new \TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Adam Schubert');
        $pdf->SetTitle(sprintf('Zasilkovna Label %s', implode(', ', $packageNumbers)));
        $pdf->SetSubject(sprintf('Zasilkovna Label %s', implode(', ', $packageNumbers)));
        $pdf->SetKeywords('Zasilkovna');
        $pdf->SetFont('freeserif');
        $pdf->setFontSubsetting(true);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);


        $quarterPosition = LabelPosition::TOP_LEFT;
        /** @var PacketAttributes $packetAttribute */
        foreach ($packetAttributes AS $packetAttribute) {
            switch ($decomposition) {
                case LabelDecomposition::FULL:
                    $pdf->AddPage();
                    $pdf = self::generateLabelFull($api, $pdf, $packetAttribute);
                    break;

                case LabelDecomposition::QUARTER:
                    if ($quarterPosition > LabelPosition::BOTTOM_RIGHT) {
                        $quarterPosition = LabelPosition::TOP_LEFT;
                    }

                    if ($quarterPosition == LabelPosition::TOP_LEFT) {
                        $pdf->AddPage();
                    }

                    $pdf = self::generateLabelQuarter($api, $pdf, $packetAttribute, $quarterPosition);
                    $quarterPosition++;
                    break;
            }
        }

        return $pdf->Output(null, 'S');
    }

    /**
     * @param \TCPDF $pdf
     * @param PacketAttributes $packetAttributes
     * @return \TCPDF
     */
    public static function generateLabelFull(IApi $api, \TCPDF $pdf, PacketAttributes $packetAttributes)
    {
        dump($api->senderGetReturnRouting($packetAttributes->getEshop()));
        exit();

        
        $x = 17;
        $pdf->Image(__DIR__ . '/../assets/logo.png', $x, 120, 100, '', 'PNG');

        //Contact info
        $contactInfoY = 45;
        $pdf->SetFont($pdf->getFontFamily(), '', 20);
        $pdf->Text($x, $contactInfoY, 'Modrá linka: 844 775 775');
        $pdf->Text($x, $contactInfoY + 10, 'E-mail: info@ppl.cz');
        $pdf->Text($x, $contactInfoY + 20, 'https://www.ppl.cz');


        //Barcode
        $x = 140; //65
        $y = 10; //110

        $pdf->write1DBarcode('Z'.$packetAttributes->getId(), 'C128', $x, $y, 140, 60, 0.3, ['stretch' => true]);

        //Barcode number

        $x = 90;
        $y = 84;
        $pdf->SetFont($pdf->getFontFamily(), '', 23);
        $pdf->Text($x, $y, $packetAttributes->getId());

                // Stop Transformation

                //Barcode number
        /*
                $x = 90;
                $y = 84;
                $pdf->SetFont($pdf->getFontFamily(), '', 23);
                $pdf->Text($x, $y, $packetAttributes->getId());
                // Stop Transformation

                //Prijemce
                $pdf->SetFont($pdf->getFontFamily(), '', 25);

                $pdf->Text(110, 9, 'Příjemce:');

                $x = 120;
                $y = 25;

                $pdf->Text($x, $y, $packetAttributes->getCompany());

                $pdf->Text($x, $y + 10, $packetAttributes->getEmail());
                $pdf->Text($x, $y + 20, $packetAttributes->getStreet());
                $pdf->Text($x, $y + 30, $packetAttributes->getCity());

                $pdf->SetFont($pdf->getFontFamily(), 'B', 55);
                $pdf->Text($x, $y + 40, $packetAttributes->getZip());

                $pdf->SetFont($pdf->getFontFamily(), '', 25);
                $pdf->Text($x, $y + 63, sprintf('Tel.: %s', $packetAttributes->getPhone()));

                $pdf->MultiCell(173, 80, '', ['LTRB' => ['width' => 1]], 'L', 0, 0, 112, 21, true, 0, false, true, 0);
                $pdf->SetFont($pdf->getFontFamily(), 'B', 60);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(0, 0, 0);

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFillColor(255, 255, 255);

                //Sender
                $pdf->SetFont($pdf->getFontFamily(), '', 25);
                $pdf->Text(112, 105, 'Odesílatel:');

                $x = 120;
                $y = 120;
                $pdf->Text($x, $y, $packetAttributes->getName());

                $pdf->Text($x, $y + 10, $packetAttributes->getSurname());

                $pdf->Text($x, $y + 20, $packetAttributes->getStreet());

                $pdf->Text($x, $y + 30, sprintf('%s %s', $packetAttributes->getZip(), $packetAttributes->getCity()));

                $pdf->MultiCell(173, 48, '', ['LTRB' => ['width' => 1]], 'L', 0, 0, 112, 117, true, 0, false, true, 0);
        */
        return $pdf;
    }

    /**
     * @param \TCPDF $pdf
     * @param PacketAttributes $packetAttributes
     * @param int $position
     * @return \TCPDF
     * @throws \Exception
     */
    public static function generateLabelQuarter($api, \TCPDF $pdf, PacketAttributes $packetAttributes, $position = LabelPosition::TOP_LEFT)
    {
        if (!in_array($position, [1, 2, 3, 4])) {
            throw new \Exception('Unknow position');
        }

        switch ($position) {
            default:
            case LabelPosition::TOP_LEFT:
                $xPositionOffset = 0;
                $yPositionOffset = 0;
                break;

            case LabelPosition::TOP_RIGHT:
                $xPositionOffset = 150;
                $yPositionOffset = 0;
                break;

            case LabelPosition::BOTTOM_LEFT:
                $xPositionOffset = 0;
                $yPositionOffset = 98;
                break;

            case LabelPosition::BOTTOM_RIGHT:
                $xPositionOffset = 150;
                $yPositionOffset = 98;
                break;
        }

        //Logo
        $pdf->Image(__DIR__ . '/../assets/logo.png', 3 + $xPositionOffset, 3 + $yPositionOffset, 34, '', 'PNG');

        //Contact info
        $pdf->SetFont($pdf->getFontFamily(), '', 9);
        $pdf->Text(3 + $xPositionOffset, 20 + $yPositionOffset, 'Modrá linka: 844 775 775');
        $pdf->Text(3 + $xPositionOffset, 25 + $yPositionOffset, 'E-mail: info@ppl.cz');
        $pdf->Text(3 + $xPositionOffset, 30 + $yPositionOffset, 'https://www.ppl.cz');


        //Barcode
        $pdf->StartTransform();
        $x = 34 + $xPositionOffset;
        $y = 40 + $yPositionOffset;
        $pdf->Rotate(270, $x, $y);
        $pdf->write1DBarcode($packetAttributes->getId(), 'I25+', $x, $y, 40, 30, 0.3, ['stretch' => true]);

        // Stop Transformation
        $pdf->StopTransform();

        //Barcode number
        $pdf->StartTransform();

        $x = 40 + $xPositionOffset;
        $y = 39 + $yPositionOffset;
        $pdf->Rotate(270, $x, $y);
        $pdf->SetFont($pdf->getFontFamily(), '', 13);
        $pdf->Text($x, $y, $packetAttributes->getId());
        // Stop Transformation
        $pdf->StopTransform();

        //Prijemce
        $pdf->SetFont($pdf->getFontFamily(), '', 12);
        $pdf->Text(50 + $xPositionOffset, 3 + $yPositionOffset, 'Příjemce:');

        $x = 53 + $xPositionOffset;
        $y = 10 + $yPositionOffset;
        if ($packetAttributes->getCompany()) {
            $pdf->Text($x, $y, $packetAttributes->getCompany());
        }

        $pdf->Text($x, $y + 5, $packetAttributes->getEmail());
        $pdf->Text($x, $y + 10, $packetAttributes->getStreet());
        $pdf->Text($x, $y + 15, $packetAttributes->getCity());

        $pdf->SetFont($pdf->getFontFamily(), 'B', 27);
        $pdf->Text($x, $y + 20, $packetAttributes->getZip());

        $pdf->SetFont($pdf->getFontFamily(), '', 10);
        $pdf->Text($x, $y + 33, sprintf('Tel.: %s', $packetAttributes->getPhone()));

        $pdf->MultiCell(85, 40, '', ['LTRB' => ['width' => 0.7]], 'L', 0, 0, 51 + $xPositionOffset, 9 + $yPositionOffset, true, 0, false, true, 0);
        $pdf->SetFont($pdf->getFontFamily(), 'B', 30);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        //Sender
        $pdf->SetFont($pdf->getFontFamily(), '', 12);
        $pdf->Text(50 + $xPositionOffset, 51 + $yPositionOffset, 'Odesílatel:');

        $x = 53 + $xPositionOffset;
        $y = 58 + $yPositionOffset;
        $pdf->SetFont($pdf->getFontFamily(), '', 10);
        $pdf->Text($x, $y, $packetAttributes->getName());

        $pdf->SetFont($pdf->getFontFamily(), '', 10);
        $pdf->Text($x, $y + 5, $packetAttributes->getSurname());

        $pdf->SetFont($pdf->getFontFamily(), '', 10);
        $pdf->Text($x, $y + 10, $packetAttributes->getStreet());

        $pdf->SetFont($pdf->getFontFamily(), '', 10);
        $pdf->Text($x, $y + 15, sprintf('%s %s', $packetAttributes->getZip(), $packetAttributes->getCity()));

        $pdf->SetFont($pdf->getFontFamily(), 'B', 13);
        $pdf->MultiCell(85, 23, '', ['LTRB' => ['width' => 0.7]], 'L', 0, 0, 51 + $xPositionOffset, 57 + $yPositionOffset, true, 0, false, true, 0);

        return $pdf;
    }
}