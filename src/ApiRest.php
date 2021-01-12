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
		if (($apiKey = trim($apiKey)) === '') {
			throw new \InvalidArgumentException('API key can not be empty.');
		}
		if (strlen($apiKey) < 5) {
			throw new \InvalidArgumentException('API key "' . $apiKey . '" is too short.');
		}
		$this->apiKey = $apiKey;
	}


	public function packetAttributesValid(PacketAttributes $attributes): iterable
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	public function packetClaimAttributesValid(ClaimAttributes $attributes): iterable
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	public function createPacket(PacketAttributes $attributes): iterable
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	public function createPacketClaim(ClaimAttributes $attributes): iterable
	{
		return $this->callApi(__FUNCTION__, $attributes);
	}


	public function createShipment(int $packetId, string $customBarcode): iterable
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'customBarcode' => $customBarcode]);
	}


	public function packetStatus(int $packetId): iterable
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
	}


	public function packetTracking(int $packetId): iterable
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
	}


	public function packetGetStoredUntil(int $packetId): iterable
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId]);
	}


	public function packetSetStoredUntil(int $packetId, \DateTimeInterface $date): iterable
	{
		return $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'date' => $date->format('Y-m-d H:i:s')]);
	}


	public function barcodePng(string $barcode, ?string $savePath = null): string
	{
		$return = $this->callApi(__FUNCTION__, ['barcode' => $barcode]);
		if ($savePath !== null) {
			file_put_contents($savePath, base64_decode($return));
		}

		return $return;
	}


	public function packetLabelPdf(int $packetId, string $format = 'A7 on A4', int $offset = 0, ?string $savePath = null): string
	{
		$return = $this->callApi(__FUNCTION__, ['packetId' => $packetId, 'format' => $format, 'offset' => $offset]);
		if ($savePath !== null) {
			file_put_contents($savePath, base64_decode($return));
		}

		return $return;
	}


	/**
	 * @param int[] $packetIds
	 * @return mixed
	 */
	public function packetsLabelsPdf(array $packetIds, string $format = 'A7 on A4', int $offset = 0)
	{
		return $this->callApi(__FUNCTION__, ['packetIds' => $packetIds, 'format' => $format, 'offset' => $offset]);
	}


	public function packetCourierNumber(int $packetId): string
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
