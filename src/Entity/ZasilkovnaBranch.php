<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Entity;

use Salamek\Zasilkovna\Tool;

final class ZasilkovnaBranch implements IBranch
{
    private int $id;

    private string $name;

    private string $place;

    private string $street;

    private string $city;

    private string $zip;

    private string $country;

    private string $currency;

    private BranchStatus $status;

    private bool $displayFrontend;

    private ?string $directions;

    private ?string $directionsCar;

    private ?string $directionsPublic;

    private bool $wheelchairAccessible;

    private bool $creditCardPayment;

    private float $latitude;

    private float $longitude;

    private string $url;

    private bool $dressingRoom;

    private bool $claimAssistant;

    private bool $packetConsignment;

    private int $maxWeight;

    private string $region;

    private string $district;

    private string $labelRouting;

    private string $labelName;

    /** @var BranchPhoto[] */
    private array $photos;

    private BranchOpeningHours $openingHours;


    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->id = (int) $data['id'];
        $this->name = $data['name'];
        $this->place = $data['place'];
        $this->street = $data['street'];
        $this->city = $data['city'];
        $this->zip = $data['zip'];
        $this->country = $data['country'];
        $this->currency = $data['currency'];
        $this->status = new BranchStatus((array) ($data['status'] ?? []));
        $this->displayFrontend = $data['displayFrontend'] === '1';
        $this->directions = Tool::convertToString($data['directions'] ?? null);
        $this->directionsCar = Tool::convertToString($data['directionsCar'] ?? null);
        $this->directionsPublic = Tool::convertToString($data['directionsPublic'] ?? null);
        $this->wheelchairAccessible = $data['wheelchairAccessible'] !== 'no';
        $this->creditCardPayment = $data['creditCardPayment'] !== 'no';
        $this->latitude = (float) $data['latitude'];
        $this->longitude = (float) $data['longitude'];
        $this->url = $data['url'];
        $this->dressingRoom = $data['dressingRoom'] === '1';
        $this->claimAssistant = $data['claimAssistant'] === '1';
        $this->packetConsignment = $data['packetConsignment'] === '1';
        $this->maxWeight = (int) $data['maxWeight'];
        $this->region = $data['region'] ?? "";
        $this->district = Tool::convertToString($data['district'] ?? null);
        $this->labelRouting = Tool::convertToString($data['labelRouting']);
        $this->labelName = Tool::convertToString($data['labelName']);
        $this->openingHours = new BranchOpeningHours((array) ($data['openingHours'] ?? []));

        $photos = [];
        foreach (((array) $data['photos']) as $photo) {
            $photos[] = new BranchPhoto($photo);
        }
        $this->photos = $photos;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getNameStreet(): string
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated, use getName() instead!', E_USER_DEPRECATED);
        return $this->getName();
    }


    public function getPlace(): string
    {
        return $this->place;
    }


    public function getStreet(): string
    {
        return $this->street;
    }


    public function getCity(): string
    {
        return $this->city;
    }


    public function getZip(): string
    {
        return $this->zip;
    }


    public function getCountry(): string
    {
        return $this->country;
    }


    public function getCurrency(): string
    {
        return $this->currency;
    }


    public function getDirections(): ?string
    {
        return $this->directions;
    }


    public function getDirectionsCar(): ?string
    {
        return $this->directionsCar;
    }


    public function getDirectionsPublic(): ?string
    {
        return $this->directionsPublic;
    }


    public function isWheelchairAccessible(): bool
    {
        return $this->wheelchairAccessible;
    }

    public function isCreditCardPayment(): bool
    {
        return $this->creditCardPayment;
    }


    public function getLatitude(): float
    {
        return $this->latitude;
    }


    public function getLongitude(): float
    {
        return $this->longitude;
    }


    public function getDistanceFrom(float $latitude, float $longitude): float
    {
        static $greatCircleRadius = 6372.795;

        $thisLatRad = deg2rad($this->latitude);
        $thisLonRad = deg2rad($this->longitude);

        $fromLatRad = deg2rad($latitude);
        $fromLonRad = deg2rad($longitude);

        return acos(
            cos($thisLatRad) * cos($thisLonRad) * cos($fromLatRad) * cos($fromLonRad)
                + cos($thisLatRad) * sin($thisLonRad) * cos($fromLatRad) * sin($fromLonRad)
                + sin($thisLatRad) * sin($fromLatRad)
        ) * $greatCircleRadius;
    }


    public function getUrl(): string
    {
        return $this->url;
    }


    public function isDressingRoom(): bool
    {
        return $this->dressingRoom;
    }


    public function isClaimAssistant(): bool
    {
        return $this->claimAssistant;
    }


    public function isPacketConsignment(): bool
    {
        return $this->packetConsignment;
    }


    public function getMaxWeight(): int
    {
        return $this->maxWeight;
    }


    public function getRegion(): string
    {
        return $this->region;
    }


    public function getDistrict(): string
    {
        return $this->district;
    }


    public function getLabelRouting(): string
    {
        return $this->labelRouting;
    }


    public function getLabelName(): string
    {
        return $this->labelName;
    }


    /**
     * @return BranchPhoto[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }


    public function getOpeningHours(): BranchOpeningHours
    {
        return $this->openingHours;
    }

    public function getStatus(): BranchStatus
    {
        return $this->status;
    }

    public function isDisplayFrontend(): bool
    {
        return $this->displayFrontend;
    }
}
