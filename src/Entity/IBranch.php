<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Entity;

interface IBranch
{
    /**
     * @param mixed[] $data
     */
    public function __construct(array $data);

    public function getName(): string;

    public function getPlace(): string;

    public function getStreet(): string;

    public function getCity(): string;

    public function getZip(): string;

    public function getCountry(): string;

    public function getCurrency(): string;

    public function getDirections(): ?string;

    public function getDirectionsCar(): ?string;

    public function getDirectionsPublic(): ?string;

    public function isWheelchairAccessible(): bool;

    public function isCreditCardPayment(): bool;

    public function getLatitude(): float;

    public function getLongitude(): float;

    public function getDistanceFrom(float $latitude, float $longitude): float;

    public function getUrl(): string;

    public function isDressingRoom(): bool;

    public function isClaimAssistant(): bool;

    public function isPacketConsignment(): bool;

    public function getMaxWeight(): int;

    public function getRegion(): string;

    public function getDistrict(): string;

    public function getLabelRouting(): string;

    public function getLabelName(): string;

    /**
     * @return BranchPhoto[]
     */
    public function getPhotos(): array;

    public function getOpeningHours(): BranchOpeningHours;

    public function getStatus(): BranchStatus;

    public function isDisplayFrontend(): bool;
}
