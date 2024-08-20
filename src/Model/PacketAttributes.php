<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

use Salamek\Zasilkovna\Tool;

final class PacketAttributes implements IModel
{
    private string $number;

    private string $name;

    private string $surname;

    private float $value;

    private int $addressId;

    private ?int $id;

    private ?string $company;

    private ?string $email;

    private ?string $phone;

    private ?string $currency;

    private ?float $cod;

    private ?float $weight;

    private ?string $eshop;

    private ?bool $adultContent;

    private ?string $street;

    private ?string $houseNumber;

    private ?string $city;

    private ?string $zip;

    private ?string $carrierPickupPoint;

    private ?string $carrierService;

    private ?DispatchOrder $dispatchOrder;

    private ?string $customerBarcode;

    private ?Size $size;


    public function __construct(
        string $number,
        string $name,
        string $surname,
        float $value,
        int $addressId,
        ?int $id = null,
        ?string $company = null,
        ?string $email = null,
        ?string $phone = null,
        ?string $currency = null,
        ?float $cod = null,
        ?float $weight = null,
        ?string $eshop = null,
        bool $adultContent = null,
        ?string $street = null,
        ?string $houseNumber = null,
        ?string $city = null,
        ?string $zip = null,
        ?string $carrierPickupPoint = null,
        ?string $carrierService = null,
        ?DispatchOrder $dispatchOrder = null,
        ?string $customerBarcode = null,
        ?Size $size = null
    ) {
        $this->setNumber($number);
        $this->setName($name);
        $this->setSurname($surname);
        $this->setValue($value);
        $this->setAddressId($addressId);
        $this->setId($id);
        $this->setCompany($company);
        $this->setEmail($email);
        $this->setPhone($phone);
        $this->setCurrency($currency);
        $this->setCod($cod);
        $this->setWeight($weight);
        $this->setEshop($eshop);
        $this->setAdultContent($adultContent);
        $this->setStreet($street);
        $this->setHouseNumber($houseNumber);
        $this->setCity($city);
        $this->setZip($zip);
        $this->setCarrierPickupPoint($carrierPickupPoint);
        $this->setCarrierService($carrierService);
        $this->setDispatchOrder($dispatchOrder);
        $this->setCustomerBarcode($customerBarcode);
        $this->setSize($size);
    }


    public function getNumber(): string
    {
        return $this->number;
    }


    public function setNumber(string $number): void
    {
        $this->number = $number;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getSurname(): string
    {
        return $this->surname;
    }


    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }


    public function getValue(): float
    {
        return $this->value;
    }


    public function setValue(float $value): void
    {
        $this->value = $value;
    }


    public function getAddressId(): int
    {
        return $this->addressId;
    }


    public function setAddressId(int $addressId): void
    {
        $this->addressId = $addressId;
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id): void
    {
        $this->id = $id;
    }


    public function getCompany(): ?string
    {
        return $this->company;
    }


    public function setCompany(?string $company): void
    {
        $this->company = $company;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(?string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = sprintf('Customer e-mail "%s" is not valid.', $email);
            throw new \InvalidArgumentException($message);
        }
        $this->email = $email;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }


    public function setPhone(?string $phone, int $region = 420, bool $normalize = true): void
    {
        if ($phone && $normalize) {
            $phone = Tool::normalizePhoneNumber($phone, $region);
        }
        $this->phone = $phone;
    }


    public function getCurrency(): ?string
    {
        return $this->currency;
    }


    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }


    public function getCod(): ?float
    {
        return $this->cod;
    }


    public function setCod(?float $cod): void
    {
        $this->cod = $cod;
    }


    public function getWeight(): ?float
    {
        return $this->weight;
    }


    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
    }


    public function getEshop(): ?string
    {
        return $this->eshop;
    }


    public function setEshop(?string $eshop): void
    {
        $this->eshop = $eshop;
    }


    public function getAdultContent(): ?bool
    {
        return $this->adultContent;
    }


    public function setAdultContent(?bool $adultContent): void
    {
        $this->adultContent = $adultContent;
    }


    public function getStreet(): ?string
    {
        return $this->street;
    }


    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }


    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }


    public function setHouseNumber(?string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }


    public function setCity(?string $city): void
    {
        $this->city = $city;
    }


    public function getZip(): ?string
    {
        return $this->zip;
    }


    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }


    public function getCarrierPickupPoint(): ?string
    {
        return $this->carrierPickupPoint;
    }


    public function setCarrierPickupPoint(?string $carrierPickupPoint): void
    {
        $this->carrierPickupPoint = $carrierPickupPoint;
    }


    public function getCarrierService(): ?string
    {
        return $this->carrierService;
    }


    public function setCarrierService(?string $carrierService): void
    {
        $this->carrierService = $carrierService;
    }


    public function getDispatchOrder(): ?DispatchOrder
    {
        return $this->dispatchOrder;
    }


    public function setDispatchOrder(?DispatchOrder $dispatchOrder): void
    {
        $this->dispatchOrder = $dispatchOrder;
    }


    public function getCustomerBarcode(): ?string
    {
        return $this->customerBarcode;
    }


    public function setCustomerBarcode(?string $customerBarcode): void
    {
        $this->customerBarcode = $customerBarcode;
    }

    public function getSize() : ?Size
    {
        return $this->size;
    }

    public function setSize(?Size $size) : void
    {
        $this->size = $size;
    }


    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
