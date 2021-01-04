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


	public function __construct(string $apiKey)
	{
		if (trim($apiKey) === '') {
			throw new \RuntimeException('API key can not be empty.');
		}
		$this->apiKey = $apiKey;
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
	 * @return mixed[]|null
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
			throw new \InvalidArgumentException('Invalid argument: Object must be a entity of type "' . IModel::class . '" or array, but "' . \gettype($object) . '" given.');
		}

		$result = $this->xml2Array(
			$this->post(
				$this->array2xml($method, $xmlArray)
			)
		);
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
				throw new PacketAttributesFault($result['detail']['attributes']['fault'] ?? 'Unknown error.');
			}
			throw new RestFault(
				$result['fault'] . ': ' . ($result['string'] ?? 'Unknown error')
				. (isset($result['detail']) && $result['detail'] ? "\n" . 'Details: ' . json_encode($result['detail']) : '')
			);
		}
	}
}
