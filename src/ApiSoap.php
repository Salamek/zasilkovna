<?php

namespace Salamek\Zasilkovna;
use Salamek\Zasilkovna\Exception\WrongDataException;
use Salamek\Zasilkovna\Model\ClaimAttributes;
use Salamek\Zasilkovna\Model\PacketAttributes;

/**
 * User: Adam Schubert
 * Date: 3.8.17
 * Time: 0:27
 */
class ApiSoap extends Api
{
    /** @var null|\SoapClient */
    private $soap = null;

    private $apiPassword;

    private $apiKey;

    /** @var string */
    private $wsdl = 'http://www.zasilkovna.cz/api/soap.wsdl';

    public function __construct($apiPassword, $apiKey)
    {
        parent::__construct($apiPassword, $apiKey);
        $this->apiPassword = $apiPassword;
        $this->apiKey = $apiKey;

        try {
            $this->soap = new \SoapClient($this->wsdl);
        } catch (\Exception $e) {
            throw new \Exception('Failed to build soap client');
        }
    }

    /**
     * @param PacketAttributes $attributes
     * @return mixed
     * @throws WrongDataException
     */
    public function packetAttributesValid(PacketAttributes $attributes)
    {
        try
        {
            return $this->soap->packetAttributesValid($this->apiPassword, $attributes);
        }
        catch (\SoapFault $e)
        {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
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