<?php

namespace Salamek\Zasilkovna;
use Salamek\Zasilkovna\Model\ClaimAttributes;
use Salamek\Zasilkovna\Model\PacketAttributes;

/**
 * User: Adam Schubert
 * Date: 3.8.17
 * Time: 0:27
 */
class ApiRest extends Api
{
    private $restApiUrl = 'https://www.zasilkovna.cz/api/rest';

    private $apiPassword;

    private $apiKey;


    public function __construct($apiPassword, $apiKey)
    {
        $this->apiPassword = $apiPassword;
        $this->apiKey = $apiKey;
    }

    private function array2xml($root, array $array)
    {
        $xml = new \SimpleXMLElement('<'.$root.'/>');
        array_walk_recursive($test_array, [$xml, 'addChild']);
        return $xml->asXML();
    }

    private function xml2array($xml)
    {
        $simplexml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($simplexml);
        return json_decode($json, true);
    }

    private function post($xml)
    {
        $opts = ['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-type: text/xml',
                'content' => $xml
            ]
        ];

        $context  = stream_context_create($opts);

        return file_get_contents($this->restApiUrl, false, $context);
    }

    private function callApi($method, $object)
    {
        $dataName = get_class($object);
        $data = get_object_vars($object);

        $xmlArray = [
            'apiPassword' => $this->apiPassword,
            $dataName => $data
        ];

        $xml = $this->array2xml($method, $xmlArray);

        return $this->post($xml);
    }

    public function packetAttributesValid(PacketAttributes $attributes)
    {
        $result = $this->callApi(__FUNCTION__, $attributes);
        
        /*$xml = '<createPacket>
          <apiPassword>'.$this->apiPassword.'</apiPassword>
          <packetAttributes>
            <number>123456</number>
            <name>Petr</name>
            <surname>Novák</surname>
            <email>petr@novak.cz</email>
            <addressId>620</addressId>
            <value>145.55</value>
            <eshop>muj-eshop.cz</eshop>
          </packetAttributes>
        </createPacket>';*/

        echo(htmlspecialchars($result));
        $attributesArray = [
            //'id' => NULL,
            'number' => 44427,
            'name' => "Test",
            'surname' => "Test",
            'company' => "",
            'email' => "sadam.sg1.game@gmail.com",
            'phone' => "777978331",
            'addressId' => 620,
            'currency' => "CZK",
            'cod' => 116,
            'value' => 117,
            //'weight' => NULL,
            //'eshop' => NULL,
            //'adultContent' => FALSE,
            'street' => "Na Tabulovem Vrchu",
            'houseNumber' => "7",
            'city' => "Olomouc",
            'zip' => "77900",
            /*'carrierPickupPoint' => NULL,
            'carrierService' => NULL,
            'dispatchOrder' => NULL,
            'customerBarcode' => NULL,*/
        ];
    }

    public function packetClaimAttributesValid(ClaimAttributes $attributes)
    {
        return $this->soap->packetClaimAttributesValid($this->apiPassword, $attributes);
    }

    public function createPacket(PacketAttributes $attributes)
    {
        return $this->soap->createPacket($this->apiPassword, $attributes);
    }

    public function createPacketClaim(ClaimAttributes $attributes)
    {
        return $this->soap->createPacketClaim($this->apiPassword, $attributes);
    }

    public function createShipment(/*int*/ $packetId, /*string*/ $customBarcode)
    {
        return $this->soap->createShipment($this->apiPassword, $packetId, $customBarcode);
    }

    public function packetStatus(/*int*/ $packetId)
    {
        return $this->soap->packetStatus($this->apiPassword, $packetId);
    }

    public function packetTracking(/*int*/ $packetId)
    {
        return $this->soap->packetTracking($this->apiPassword, $packetId);
    }

    public function packetGetStoredUntil(/*int*/ $packetId)
    {
        return $this->soap->packetGetStoredUntil($this->apiPassword, $packetId);
    }

    public function packetSetStoredUntil(/*int*/ $packetId, \DateTimeInterface $date)
    {
        return $this->soap->packetSetStoredUntil($this->apiPassword, $packetId, $date);
    }

    public function barcodePng(/*string*/ $barcode)
    {
        return $this->soap->barcodePng($this->apiPassword, $barcode);
    }

    public function packetLabelPdf(/*int*/ $packetId, /*string*/ $format, /*int*/ $offset)
    {
        return $this->soap->packetLabelPdf($this->apiPassword, $packetId, $format, $offset);
    }

    public function packetsLabelsPdf(array/*PacketIds*/ $packetIds, /*string*/ $format, /*int*/ $offset)
    {
        return $this->soap->packetsLabelsPdf($this->apiPassword, $packetIds, $format, $offset);
    }

    public function packetCourierNumber(/*int*/ $packetId)
    {
        return $this->soap->packetCourierNumber($this->apiPassword, $packetId);
    }

    public function senderGetReturnRouting(/*string*/ $senderLabel)
    {
        return $this->soap->senderGetReturnRouting($this->apiPassword, $senderLabel);
    }
}