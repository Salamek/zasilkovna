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
     * @param string[] $packetIds
     * @return mixed
     */
    public function createShipment(array $packetIds, string $customBarcode);

    /**
     * @return mixed
     */
    public function packetStatus(string $packetId);

    /**
     * @return mixed
     */
    public function packetTracking(string $packetId);

    /**
     * @return mixed
     */
    public function packetGetStoredUntil(string $packetId);

    /**
     * @return mixed
     */
    public function packetSetStoredUntil(string $packetId, DateTimeInterface $date);

    /**
     * @return mixed
     */
    public function barcodePng(string $barcode);

    /**
     *
     * @param string $packetId
     * @param string $format
     * @param int $offset
     * @return string
     */
    public function packetLabelPdf(string $packetId, string $format, int $offset): string;

    /**
     * @param string[] $packetIds
     * @return mixed
     */
    public function packetsLabelsPdf(array $packetIds, string $format, int $offset);

    /**
     * @return mixed
     */
    public function packetCourierNumber(string $packetId);

    /**
     * @return mixed
     */
    public function senderGetReturnRouting(string $senderLabel);
}
