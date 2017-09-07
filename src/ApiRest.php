<?php

namespace Salamek\Zasilkovna;
use Salamek\Zasilkovna\Exception\RestFault;
use Salamek\Zasilkovna\Model\ClaimAttributes;
use Salamek\Zasilkovna\Model\IModel;
use Salamek\Zasilkovna\Model\PacketAttributes;
use Spatie\ArrayToXml\ArrayToXml;

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

    /**
     * ApiRest constructor.
     * @param $apiPassword
     * @param $apiKey
     */
    public function __construct($apiPassword, $apiKey)
    {
        parent::__construct($apiPassword, $apiKey);
        $this->apiPassword = $apiPassword;
        $this->apiKey = $apiKey;
    }

    /**
     * @param $root
     * @param array $array
     * @return string
     */
    private function array2xml($root, array $array)
    {
        return ArrayToXml::convert($array, $root);
    }

    /**
     * @param $xml
     * @return mixed
     */
    private function xml2object($xml)
    {
        $simplexml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($simplexml);
        return json_decode($json, false);
    }

    /**
     * @param $xml
     * @return mixed
     */
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

    /**
     * @param $method
     * @param IModel $object
     * @return mixed
     * @throws RestFault
     */
    private function callApi($method, IModel $object)
    {
        $path = explode('\\', get_class($object));
        $dataName =  lcfirst(array_pop($path));
        $data = $object->toArray();

        $xmlArray = [
            'apiPassword' => $this->apiPassword,
            $dataName => $data
        ];

        $xml = $this->array2xml($method, $xmlArray);

        $resultXml = $this->post($xml);

        $result = $this->xml2object($resultXml);
        $this->proccessResult($result);

        return (isset($result->result) ? $result->result : null);
    }

    /**
     * @param array $result
     * @throws RestFault
     */
    private function proccessResult($result)
    {
        if ($result->status == 'fault')
        {
            throw new RestFault($result->fault.': '.$result->string.json_encode($result->detail));
        }
    }

    /**
     * @param PacketAttributes $attributes
     * @return mixed
     */
    public function packetAttributesValid(PacketAttributes $attributes)
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }

    /**
     * @param ClaimAttributes $attributes
     * @return mixed
     */
    public function packetClaimAttributesValid(ClaimAttributes $attributes)
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }

    /**
     * @param PacketAttributes $attributes
     * @return mixed
     */
    public function createPacket(PacketAttributes $attributes)
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }

    /**
     * @param ClaimAttributes $attributes
     * @return mixed
     */
    public function createPacketClaim(ClaimAttributes $attributes)
    {
        return $this->callApi(__FUNCTION__, $attributes);
    }

    public function createShipment(/*int*/ $packetId, /*string*/ $customBarcode)
    {
    }

    public function packetStatus(/*int*/ $packetId)
    {
    }

    public function packetTracking(/*int*/ $packetId)
    {
    }

    public function packetGetStoredUntil(/*int*/ $packetId)
    {
    }

    public function packetSetStoredUntil(/*int*/ $packetId, \DateTimeInterface $date)
    {
    }

    public function barcodePng(/*string*/ $barcode)
    {
    }

    public function packetLabelPdf(/*int*/ $packetId, /*string*/ $format, /*int*/ $offset)
    {
    }

    public function packetsLabelsPdf(array/*PacketIds*/ $packetIds, /*string*/ $format, /*int*/ $offset)
    {
    }

    public function packetCourierNumber(/*int*/ $packetId)
    {
    }

    public function senderGetReturnRouting(/*string*/ $senderLabel)
    {
    }
}