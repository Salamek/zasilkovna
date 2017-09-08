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
    /** @var IApi */
    private $api;

    /** @var Branch */
    private $branch;

    /**
     * Label constructor.
     * @param IApi $api
     * @param Branch $branch
     */
    public function __construct(IApi $api, Branch $branch)
    {
        $this->api = $api;
        $this->branch = $branch;
    }

    private function id2packageNumber($id)
    {
        return 'Z'.$id;
    }

    private function parsePackageNumber($packageNumber)
    {
        if (strpos($packageNumber, 'Z') !=0)
        {
            throw new \Exception('Invalid package number');
        }

        $parts = str_split($packageNumber);

        return [
            $parts[0],
            $parts[1].$parts[2].$parts[3],
            $parts[4].$parts[5].$parts[6].$parts[7],
            $parts[8].$parts[9].$parts[10],
        ];
    }

    /**
     * @param PacketAttributes[] $packetAttributes
     * @param int $decomposition
     * @return string
     * @throws \Exception
     */
    public function generateLabels(array $packetAttributes, $decomposition = LabelDecomposition::FULL)
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
                    $pdf = $this->generateLabelFull($pdf, $packetAttribute);
                    break;

                case LabelDecomposition::QUARTER:
                    if ($quarterPosition > LabelPosition::BOTTOM_RIGHT) {
                        $quarterPosition = LabelPosition::TOP_LEFT;
                    }

                    if ($quarterPosition == LabelPosition::TOP_LEFT) {
                        $pdf->AddPage();
                    }

                    $pdf = $this->generateLabelQuarter($pdf, $packetAttribute, $quarterPosition);
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
    public function generateLabelFull(\TCPDF $pdf, PacketAttributes $packetAttributes)
    {
        $returnRouting = $this->api->senderGetReturnRouting($packetAttributes->getEshop());
        $branch = $this->branch->find($packetAttributes->getAddressId());

        /*
        dump();
        dump();
        */
        //$packetAttributes->getEshop()
        //$packetAttributes->getNumber();

        /*stdClass #ca63
        routingSegment => array (2)
        0 => "=A--0--085=depo=" (16)
        1 => "+10N+>" (6)*/

        /*id => "620" (3)
name => "Litovel, Příčná" (19)
nameStreet => "Litovel, Příčná 1348/5" (26)
place => "Davcom" (6)
street => "Příčná 1348/5" (17)
city => "Litovel" (7)
zip => "784 01" (6)
country => "cz" (2)
currency => "CZK" (3)
directions => "<p>Pobočka se nachází přímo u velikého parkoviště naproti obchodu Billa.</p>" (84)
wheelchairAccessible => "no" (2)
latitude => "49.69901" (8)
longitude => "17.07290" (8)
url => "https://www.zasilkovna.cz/pobocky/litovel-pricna" (48)
dressingRoom => "0"
claimAssistant => "1"
packetConsignment => "1"
maxWeight => "10" (2)
region => "Olomoucký kraj" (15)
district => "Olomouc" (7)
labelRouting => "M-M01-620" (9)
labelName => "Litovel, Příčná" (19)
photos => array (3)
0 => array (2)
thumbnail => "https://www.zasilkovna.cz/images/branch/thumb/IMG_20151012_153804.jpg" (69)
normal => "https://www.zasilkovna.cz/images/branch/normal/IMG_20151012_153804.jpg" (70)
1 => array (2)
thumbnail => "https://www.zasilkovna.cz/images/branch/thumb/IMG_20151012_153822.jpg" (69)
normal => "https://www.zasilkovna.cz/images/branch/normal/IMG_20151012_153822.jpg" (70)
2 => array (2)
thumbnail => "https://www.zasilkovna.cz/images/branch/thumb/IMG_20151012_153842.jpg" (69)
normal => "https://www.zasilkovna.cz/images/branch/normal/IMG_20151012_153842.jpg" (70)
openingHours => array (5)
compactShort => "<strong>Po–Pá</strong> 08:00–12:30, 13:30–17:30<br /><strong style='color: red;'>28. 9.</strong> zavřeno" (112)
compactLong => "<strong>Po–Pá</strong> 08:00–12:30, 13:30–17:30<br /><strong style='color: red;'>28. 9.</strong> zavřeno" (112)
tableLong => "<table class='packetery-hours'><tr><th>Po</th><td>08:00–12:30</td><td>13:30–17:30</td></tr>
<tr><th>Út</th><td>08:00–12:30</td><td>13:30–17:30</td></t ... " (493)
regular => array (7)
exceptions => array (1)
exception => array (2) [ ... ]*/

        $x = 17;
        $pdf->Image(__DIR__ . '/../assets/logo.png', $x + 10, 120, 100, '', 'PNG');

        //Contact info
        $contactInfoX = $x + 10;
        $contactInfoY = 10;

        $pdf->SetFont($pdf->getFontFamily(), '', 31);
        $pdf->Text($contactInfoX, $contactInfoY, $packetAttributes->getEshop());
        $pdf->Text($contactInfoX, $contactInfoY + 12, 'obj.');
        $pdf->SetFont($pdf->getFontFamily(), 'B', 31);
        $pdf->Text($contactInfoX + 20, $contactInfoY + 12, $packetAttributes->getNumber());
        $pdf->SetFont($pdf->getFontFamily(), '', 31);
        $pdf->Text($contactInfoX, $contactInfoY + 27, $returnRouting->routingSegment[0]);
        $pdf->Text($contactInfoX, $contactInfoY + 39, $returnRouting->routingSegment[1]);

        //Sender text
        $pdf->StartTransform();
        $sTextX = $x - 10;
        $sTextY = $contactInfoY + 51;
        $pdf->Rotate(90, $sTextX, $sTextY);
        $pdf->SetFont($pdf->getFontFamily(), '', 29);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Text($sTextX, $sTextY, 'Odesílatel');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->StopTransform();

        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetDrawColor(150, 150, 150);
        $pdf->MultiCell(2, 48, '', ['LTRB' => ['width' => 1]], 'L', true, 0, 21, 15, true, 0, false, true, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(0, 0, 0);

        //Barcode
        $x = 140; //65
        $y = 10; //110

        $pdf->write1DBarcode($this->id2packageNumber($packetAttributes->getId()), 'C128', $x, $y, 140, 60, 0.3, ['stretch' => true]);

        //Barcode number

        $x = 182;
        $y = 70;
        $pdf->SetFont($pdf->getFontFamily(), '', 23);

        $parts = $this->parsePackageNumber($this->id2packageNumber($packetAttributes->getId()));

        $pdf->Text($x, $y, $parts[0].' '.$parts[1].' '.$parts[2]);

        $pdf->SetFont($pdf->getFontFamily(), 'B', 27);
        $pdf->Text($x + 40, $y, $parts[3]);
        $pdf->SetFont($pdf->getFontFamily(), '', 23);


        //Prijemce

        $pTextX = 150;
        $pTextY = 100;
        $pdf->SetFont($pdf->getFontFamily(), '', 29);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Text($pTextX, $pTextY, 'Příjemce');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetDrawColor(150, 150, 150);
        $pdf->MultiCell(48, 2, '', ['LTRB' => ['width' => 1]], 'L', true, 0, 148, 113, false, 0, false, true, 2);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(0, 0, 0);

        $pdf->SetFont($pdf->getFontFamily(), 'B', 37);
        $pdf->Text($pTextX - 3, $pTextY + 16, $packetAttributes->getName().' '.$packetAttributes->getSurname());

        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->MultiCell(70, 2, $branch['labelRouting'], ['LTRB' => ['width' => 1]], 'L', true, 0, $pTextX - 2, $pTextY + 32, false, 0, false, true, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont($pdf->getFontFamily(), '', 29);
        $pdf->Text($pTextX - 3, $pTextY + 50, $branch['name']);

        return $pdf;
    }

    /**
     * @param \TCPDF $pdf
     * @param PacketAttributes $packetAttributes
     * @param int $position
     * @return \TCPDF
     * @throws \Exception
     */
    public function generateLabelQuarter(\TCPDF $pdf, PacketAttributes $packetAttributes, $position = LabelPosition::TOP_LEFT)
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

        $returnRouting = $this->api->senderGetReturnRouting($packetAttributes->getEshop());
        $branch = $this->branch->find($packetAttributes->getAddressId());

        //Logo
        $pdf->Image(__DIR__ . '/../assets/logo.png', 3 + $xPositionOffset, 50 + $yPositionOffset, 60, '', 'PNG');




        //Sender

        $pdf->StartTransform();
        $sTextX = 3 + $xPositionOffset;
        $sTextY = 33 + $yPositionOffset;
        $pdf->Rotate(90, $sTextX, $sTextY);
        $pdf->SetFont($pdf->getFontFamily(), '', 16);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Text($sTextX, $sTextY, 'Odesílatel');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->StopTransform();

        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetDrawColor(150, 150, 150);
        $pdf->MultiCell(0.3, 30, '', ['LTRB' => ['width' => 1]], 'L', true, 0, 10 + $xPositionOffset, 5 + $yPositionOffset, true, 0, false, true, 0.3);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(0, 0, 0);

        $pdf->SetFont($pdf->getFontFamily(), '', 16);
        $pdf->Text(12 + $xPositionOffset, 3 + $yPositionOffset, $packetAttributes->getEshop());
        $pdf->Text(12 + $xPositionOffset, 10 + $yPositionOffset, 'obj.');
        $pdf->SetFont($pdf->getFontFamily(), 'B', 16);
        $pdf->Text(22 + $xPositionOffset, 10 + $yPositionOffset, $packetAttributes->getNumber());
        $pdf->SetFont($pdf->getFontFamily(), '', 16);
        $pdf->Text(12 + $xPositionOffset, 20 + $yPositionOffset, $returnRouting->routingSegment[0]);
        $pdf->Text(12 + $xPositionOffset, 27 + $yPositionOffset, $returnRouting->routingSegment[1]);


        //Barcode
        $x = 65 + $xPositionOffset;
        $y = 5 + $yPositionOffset;
        $pdf->write1DBarcode($this->id2packageNumber($packetAttributes->getId()), 'C128', $x, $y, 70, 30, 0.3, ['stretch' => true]);

        $x = 83 + $xPositionOffset;
        $y = 36 + $yPositionOffset;
        $pdf->SetFont($pdf->getFontFamily(), '', 13);
        $parts = $this->parsePackageNumber($this->id2packageNumber($packetAttributes->getId()));
        $pdf->Text($x, $y, $parts[0].' '.$parts[1].' '.$parts[2]);
        $pdf->SetFont($pdf->getFontFamily(), 'B', 17);
        $pdf->Text($x + 22, $y, $parts[3]);


        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetDrawColor(150, 150, 150);
        $pdf->MultiCell(26, 0.1, '', ['LTRB' => ['width' => 1]], 'L', true, 0, 74 + $xPositionOffset, 58 + $yPositionOffset, false, 0, false, true, 0.1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(0, 0, 0);

        //Příjemce
        $pdf->SetFont($pdf->getFontFamily(), '', 16);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Text(75 + $xPositionOffset, 50 + $yPositionOffset, 'Příjemce:');
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont($pdf->getFontFamily(), 'B', 22);
        $pdf->Text(73 + $xPositionOffset, 60 + $yPositionOffset, $packetAttributes->getName().' '.$packetAttributes->getSurname());

        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->MultiCell(42, 2, $branch['labelRouting'], ['LTRB' => ['width' => 1]], 'L', true, 0, 73 + $xPositionOffset, 70 + $yPositionOffset, false, 0, false, true, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont($pdf->getFontFamily(), '', 16);
        $pdf->Text(73 + $xPositionOffset, 82 + $yPositionOffset, $branch['name']);


        return $pdf;
    }
}