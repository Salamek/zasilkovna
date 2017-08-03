<?php

namespace Salamek\Zasilkovna;
use Salamek\Zasilkovna\Model\ClaimAttributes;
use Salamek\Zasilkovna\Model\PacketAttributes;

/**
 * User: Adam Schubert
 * Date: 3.8.17
 * Time: 0:27
 */
class Api
{
    /** @var null|\SoapClient */
    private $soap = null;

    private $apiPassword;

    /** @var string */
    private $wsdl = 'http://www.zasilkovna.cz/api/soap.wsdl';

    public function __construct($apiPassword)
    {
        $this->apiPassword = $apiPassword;

        try {
            $this->soap = new \SoapClient($this->wsdl);
        } catch (\Exception $e) {
            throw new \Exception('Failed to build soap client');
        }
    }

    public function packetAttributesValid(PacketAttributes $attributes)
    {
        $this->soap->packetAttributesValid($this->apiPassword, $attributes);
    }

    public function packetClaimAttributesValid(ClaimAttributes $attributes)
    {
        $this->soap->packetClaimAttributesValid($this->apiPassword, $attributes);
    }

    public function createPacket(PacketAttributes $attributes)
    {
        $this->soap->createPacket($this->apiPassword, $attributes);
    }

    public function createPacketClaim(ClaimAttributes $attributes)
    {
        $this->soap->createPacketClaim($this->apiPassword, $attributes);
    }

    public function createShipment(/*int*/ $packetId, /*string*/ $customBarcode)
    {
        $this->soap->createShipment($this->apiPassword, $packetId, $customBarcode);
    }

    public function packetStatus(/*int*/ $packetId)
    {
        $this->soap->packetStatus($this->apiPassword, $packetId);
    }

    public function packetTracking(/*int*/ $packetId)
    {
        $this->soap->packetTracking($this->apiPassword, $packetId);
    }

    public function packetGetStoredUntil(/*int*/ $packetId)
    {
        $this->soap->packetGetStoredUntil($this->apiPassword, $packetId);
    }

    public function packetSetStoredUntil(/*int*/ $packetId, \DateTimeInterface $date)
    {
        $this->soap->packetSetStoredUntil($this->apiPassword, $packetId, $date);
    }

    public function barcodePng(/*string*/ $barcode)
    {
        $this->soap->barcodePng($this->apiPassword, $barcode);
    }

    public function packetLabelPdf(/*int*/ $packetId, /*string*/ $format, /*int*/ $offset)
    {
        $this->soap->packetLabelPdf($this->apiPassword, $packetId, $format, $offset);
    }

    public function packetsLabelsPdf(array/*PacketIds*/ $packetIds, /*string*/ $format, /*int*/ $offset)
    {
        $this->soap->packetsLabelsPdf($this->apiPassword, $packetIds, $format, $offset);
    }

    public function packetCourierNumber(/*int*/ $packetId)
    {
        $this->soap->packetCourierNumber($this->apiPassword, $packetId);
    }

    public function senderGetReturnRouting(/*string*/ $senderLabel)
    {
        $this->soap->senderGetReturnRouting($this->apiPassword, $senderLabel);
    }
}