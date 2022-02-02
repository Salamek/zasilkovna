<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;

use Salamek\Zasilkovna\Exception\WrongDataException;
use Salamek\Zasilkovna\Model\ClaimAttributes;
use Salamek\Zasilkovna\Model\PacketAttributes;

final class ApiSoap implements IApi
{
    private ?\SoapClient $soap;

    private string $apiKey;


    public function __construct(string $apiKey)
    {
        $apiKey = \trim($apiKey);
        if ($apiKey === '') {
            throw new \InvalidArgumentException('API key can not be empty.');
        }
        if (\strlen($apiKey) < 5) {
            throw new \InvalidArgumentException('API key "' . $apiKey . '" is too short.');
        }
        $this->apiKey = $apiKey;
        try {
            $this->soap = new \SoapClient('http://www.zasilkovna.cz/api/soap.wsdl');
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Failed to build soap client');
        }
    }


    /**
     * @return mixed
     * @throws WrongDataException
     */
    public function packetAttributesValid(PacketAttributes $attributes)
    {
        try {
            return $this->soap->packetAttributesValid($this->apiKey, $attributes);
        } catch (\SoapFault $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }


    /**
     * @return mixed
     */
    public function packetClaimAttributesValid(ClaimAttributes $attributes)
    {
        return $this->soap->packetClaimAttributesValid($this->apiKey, $attributes);
    }


    /**
     * @return mixed
     */
    public function createPacket(PacketAttributes $attributes)
    {
        return $this->soap->createPacket($this->apiKey, $attributes);
    }


    /**
     * @return mixed
     */
    public function createPacketClaim(ClaimAttributes $attributes)
    {
        return $this->soap->createPacketClaim($this->apiKey, $attributes);
    }


    /**
     * @return mixed
     */
    public function createShipment(int $packetId, string $customBarcode)
    {
        return $this->soap->createShipment($this->apiKey, $packetId, $customBarcode);
    }


    /**
     * @return mixed
     */
    public function packetStatus(int $packetId)
    {
        return $this->soap->packetStatus($this->apiKey, $packetId);
    }


    /**
     * @return mixed
     */
    public function packetTracking(int $packetId)
    {
        return $this->soap->packetTracking($this->apiKey, $packetId);
    }


    /**
     * @return mixed
     */
    public function packetGetStoredUntil(int $packetId)
    {
        return $this->soap->packetGetStoredUntil($this->apiKey, $packetId);
    }


    /**
     * @return mixed
     */
    public function packetSetStoredUntil(int $packetId, \DateTimeInterface $date)
    {
        return $this->soap->packetSetStoredUntil($this->apiKey, $packetId, $date);
    }


    /**
     * @return mixed
     */
    public function barcodePng(string $barcode)
    {
        return $this->soap->barcodePng($this->apiKey, $barcode);
    }


    /**
     * @return mixed
     */
    public function packetLabelPdf(int $packetId, string $format, int $offset)
    {
        return $this->soap->packetLabelPdf($this->apiKey, $packetId, $format, $offset);
    }


    /**
     * @param int[] $packetIds
     * @return mixed
     */
    public function packetsLabelsPdf(array $packetIds, string $format, int $offset)
    {
        return $this->soap->packetsLabelsPdf($this->apiKey, $packetIds, $format, $offset);
    }


    /**
     * @return mixed
     */
    public function packetCourierNumber(int $packetId)
    {
        return $this->soap->packetCourierNumber($this->apiKey, $packetId);
    }


    /**
     * @return mixed
     */
    public function senderGetReturnRouting(string $senderLabel)
    {
        return $this->soap->senderGetReturnRouting($this->apiKey, $senderLabel);
    }
}
