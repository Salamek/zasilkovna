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
	private string $apiPassword;


	public function __construct(string $apiPassword)
	{
		$this->apiPassword = $apiPassword;
	}


	/**
	 * @return mixed
	 */
	public function packetAttributesValid(PacketAttributes $attributes)
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	/**
	 * @return mixed
	 */
	public function packetClaimAttributesValid(ClaimAttributes $attributes)
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	/**
	 * @return mixed
	 */
	public function createPacket(PacketAttributes $attributes)
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	/**
	 * @return mixed
	 */
	public function createPacketClaim(ClaimAttributes $attributes)
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	/**
	 * @return mixed
	 */
	public function createShipment(int $packetId, string $customBarcode)
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'customBarcode' => $customBarcode]);
	}


	/**
	 * @return mixed
	 */
	public function packetStatus(int $packetId)
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
	}


	/**
	 * @return mixed
	 */
	public function packetTracking(int $packetId)
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
	}


	/**
	 * @return mixed
	 */
	public function packetGetStoredUntil(int $packetId)
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
	}


	/**
	 * @return mixed
	 */
	public function packetSetStoredUntil(int $packetId, \DateTimeInterface $date)
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'date' => $date->format('Y-m-d H:i:s')]);
	}


	/**
	 * @return mixed
	 */
	public function barcodePng(string $barcode)
	{
		return $this->callApi(__FUNCTION__, ['barcode' => $barcode]);
	}


	/**
	 * @return mixed
	 */
	public function packetLabelPdf(int $packetId, string $format, int $offset)
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'format' => $format, 'offset' => $offset]);
	}


	/**
	 * @param int[] $packetIds
	 * @return mixed
	 */
	public function packetsLabelsPdf(array $packetIds, string $format, int $offset)
	{
		return $this->callApi(__FUNCTION__, ['packetIds' => $packetIds, 'format' => $format, 'offset' => $offset]);
	}


	/**
	 * @return mixed
	 */
	public function packetCourierNumber(int $packetId)
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
	 * @param $root
	 * @param array $array
	 * @return string
	 */
	private function array2xml($root, array $array): string
	{
		return ArrayToXml::convert($array, $root);
	}


	/**
	 * @param $xml
	 * @return mixed
	 */
	private function xml2object($xml)
	{
		$simplexml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		$json = json_encode($simplexml);

		return json_decode($json, false);
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
	 * @param IModel|array $object
	 * @return mixed
	 * @throws RestFault
	 */
	private function callApi(string $method, $object)
	{
		$xmlArray = [
			'apiPassword' => $this->apiPassword,
		];

		if ($object instanceof IModel) {
			$path = explode('\\', get_class($object));
			$dataName = lcfirst(array_pop($path));
			$data = $object->toArray();
			$xmlArray[$dataName] = $data;
		} elseif (is_array($object)) {
			$xmlArray += $object;
		}

		$result = $this->xml2object(
			$this->post(
				$this->array2xml($method, $xmlArray)
			)
		);
		$this->proccessResult($result);

		return $result->result ?? null;
	}


	/**
	 * @throws RestFault|PacketAttributesFault
	 */
	private function proccessResult(array $result)
	{
		if ($result->status === 'fault') {
			if ($result->fault === 'PacketAttributesFault') {
				throw new PacketAttributesFault($result->detail->attributes->fault);
			}
			throw new RestFault($result->fault . ': ' . $result->string . json_encode($result->detail));
		}
	}
}
