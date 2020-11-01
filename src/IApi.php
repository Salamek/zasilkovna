<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;


use DateTimeInterface;
use Salamek\Zasilkovna\Model\ClaimAttributes;
use Salamek\Zasilkovna\Model\PacketAttributes;

interface IApi
{
	/**
	 * @return mixed
	 */
	public function packetAttributesValid(PacketAttributes $attributes);

	/**
	 * @return mixed
	 */
	public function packetClaimAttributesValid(ClaimAttributes $attributes);

	/**
	 * @return mixed
	 */
	public function createPacket(PacketAttributes $attributes);

	/**
	 * @return mixed
	 */
	public function createPacketClaim(ClaimAttributes $attributes);

	/**
	 * @return mixed
	 */
	public function createShipment(int $packetId, string $customBarcode);

	/**
	 * @return mixed
	 */
	public function packetStatus(int $packetId);

	/**
	 * @return mixed
	 */
	public function packetTracking(int $packetId);

	/**
	 * @return mixed
	 */
	public function packetGetStoredUntil(int $packetId);

	/**
	 * @return mixed
	 */
	public function packetSetStoredUntil(int $packetId, DateTimeInterface $date);

	/**
	 * @return mixed
	 */
	public function barcodePng(string $barcode);

	/**
	 * @return mixed
	 */
	public function packetLabelPdf(int $packetId, string $format, int $offset);

	/**
	 * @param int[] $packetIds
	 * @return mixed
	 */
	public function packetsLabelsPdf(array $packetIds, string $format, int $offset);

	/**
	 * @return mixed
	 */
	public function packetCourierNumber(int $packetId);

	/**
	 * @return mixed
	 */
	public function senderGetReturnRouting(string $senderLabel);
}
