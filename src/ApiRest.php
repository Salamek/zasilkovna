<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;

use Salamek\Zasilkovna\Exception\PacketAttributesFault;
use Salamek\Zasilkovna\Exception\RestFault;
use Salamek\Zasilkovna\Model\ClaimAttributes;
use Salamek\Zasilkovna\Model\IModel;
use Salamek\Zasilkovna\Model\PacketAttributes;
use Spatie\ArrayToXml\ArrayToXml;

final class ApiRest implements IApi
{
    private string $apiKey;

    /**
     *
     * @param string $apiKey
     * @throws \InvalidArgumentException
     */
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
    }


    /**
     *
     * @param PacketAttributes $attributes
     * @return iterable<mixed>
     */
    public function packetAttributesValid(PacketAttributes $attributes): iterable
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }


    /**
     *
     * @param ClaimAttributes $attributes
     * @return iterable<mixed>
     */
    public function packetClaimAttributesValid(ClaimAttributes $attributes): iterable
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }


    /**
     *
     * @param PacketAttributes $attributes
     * @return iterable<mixed>
     */
    public function createPacket(PacketAttributes $attributes): iterable
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }


    /**
     *
     * @param ClaimAttributes $attributes
     * @return iterable<mixed>
     */
    public function createPacketClaim(ClaimAttributes $attributes): iterable
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }


    /**
     *
     * @param string $packetId
     * @param string $customBarcode
     * @return iterable<mixed>
     */
    public function createShipment(string $packetId, string $customBarcode): iterable
    {
        return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'customBarcode' => $customBarcode]);
    }


    /**
     *
     * @param string $packetId
     * @return iterable<mixed>
     */
    public function packetStatus(string $packetId): iterable
    {
        return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
    }


    /**
     *
     * @param string $packetId
     * @return iterable<mixed>
     */
    public function packetTracking(string $packetId): iterable
    {
        return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
    }


    /**
     *
     * @param string $packetId
     * @return iterable<mixed>
     */
    public function packetGetStoredUntil(string $packetId): iterable
    {
        return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
    }


    /**
     *
     * @param string $packetId
     * @param \DateTimeInterface $date
     * @return iterable<mixed>
     */
    public function packetSetStoredUntil(string $packetId, \DateTimeInterface $date): iterable
    {
        return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'date' => $date->format('Y-m-d H:i:s')]);
    }


    /**
     *
     * @param string $barcode
     * @return string
     */
    public function barcodePng(string $barcode): string
    {
        return $this->callApi(__FUNCTION__, ['barcode' => $barcode]);
    }


    /**
     *
     * @param string $packetId
     * @param string $format
     * @param int $offset
     * @return string
     */
    public function packetLabelPdf(string $packetId, string $format = 'A7 on A4', int $offset = 0): string
    {
        return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'format' => $format, 'offset' => $offset]);
    }


    /**
     * @param string[] $packetIds
     * @return mixed
     */
    public function packetsLabelsPdf(array $packetIds, string $format = 'A7 on A4', int $offset = 0)
    {
        return $this->callApi(__FUNCTION__, ['packetIds' => $packetIds, 'format' => $format, 'offset' => $offset]);
    }


    /**
     *
     * @param string $packetId
     * @return string
     */
    public function packetCourierNumber(string $packetId): string
    {
        return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
    }


    /**
     * @return mixed
     */
    public function senderGetReturnRouting(string $senderLabel)
    {
        return $this->callApi(__FUNCTION__, ['senderLabel' => $senderLabel]);
    }


    /**
     * @param mixed[] $array
     * @return string
     */
    private function array2xml(string $root, array $array): string
    {
        return ArrayToXml::convert($array, $root);
    }


    /**
     * @return mixed[]
     */
    private function xml2Array(string $xml): array
    {
        $simplexml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        return json_decode(json_encode($simplexml), true, 512, JSON_THROW_ON_ERROR);
    }


    private function post(string $xml): string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: text/xml',
                'content' => $xml,
            ],
        ]);

        return file_get_contents('https://www.zasilkovna.cz/api/rest', false, $context);
    }


    /**
     * @param IModel|array|mixed $object
     * @return mixed[]|string|null
     * @throws RestFault
     */
    private function callApi(string $method, $object)
    {
        $xmlArray = [
            'apiPassword' => $this->apiKey,
        ];

        if ($object instanceof IModel) {
            $path = explode('\\', get_class($object));
            $xmlArray[lcfirst(array_pop($path))] = $object->toArray();
        } elseif (is_array($object)) {
            $xmlArray += $object;
        } else {
            $message = sprintf(
                'Invalid argument: Object must be a entity of type "%s" or array, but "%s" was given.',
                IModel::class,
                \gettype($object)
            );
            throw new \InvalidArgumentException($message);
        }

                $xmlData = $this->array2xml($method, $xmlArray);
                $xmlResponse = $this->post($xmlData);

        $result = $this->xml2Array($xmlResponse);
        $this->processResult($result);

        return $result['result'] ?? null;
    }


    /**
     * @param mixed[] $result
     * @throws RestFault|PacketAttributesFault
     */
    private function processResult(array $result): void
    {
        if (($result['status'] ?? '') === 'fault') {
            if ($result['fault'] === 'PacketAttributesFault') {
                throw new PacketAttributesFault($result['detail']['attributes']['fault']);
            }

                        $resultFaultsInfo = [$result['fault'] . ': ' . ($result['string'] ?? 'Unknown error')];

            if (isset($result['detail']) && $result['detail']) {
                $resultFaultsInfo[] = PHP_EOL . 'Details: ' . json_encode($result['detail']);
            }

            throw new RestFault(join('', $resultFaultsInfo));
        }
    }
}
