<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;


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

	private ?int $zip;

	private ?int $carrierPickupPoint;

	private ?string $carrierService;

	private ?DispatchOrder $dispatchOrder;

	private ?string $customerBarcode;


	/**
	 * @param string|int|null $zip
	 */
	public function __construct(string $number, string $name, string $surname, float $value, int $addressId, ?int $id = null, ?string $company = null, ?string $email = null, ?string $phone = null, ?string $currency = null, ?float $cod = null, ?float $weight = null, ?string $eshop = null, bool $adultContent = null, ?string $street = null, ?string $houseNumber = null, ?string $city = null, $zip = null, ?int $carrierPickupPoint = null, ?string $carrierService = null, ?DispatchOrder $dispatchOrder = null, ?string $customerBarcode = null)
	{
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
		if (\is_string($zip)) {
			$this->setZipString($zip);
		} else {
			$this->setZip($zip);
		}
		$this->setCarrierPickupPoint($carrierPickupPoint);
		$this->setCarrierService($carrierService);
		$this->setDispatchOrder($dispatchOrder);
		$this->setCustomerBarcode($customerBarcode);
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
		if ($email === null) {
			$this->email = null;

			return;
		}
		$isEmail = static function (string $value): bool {
			$atom = "[-a-z0-9!#$%&'*+/=?^_`{|}~]"; // RFC 5322 unquoted characters in local-part
			$alpha = "a-z\x80-\xFF"; // superset of IDN

			return (bool) preg_match(<<<XX
		(^
			("([ !#-[\\]-~]*|\\\\[ -~])+"|$atom+(\\.$atom+)*)  # quoted or unquoted
			@
			([0-9$alpha]([-0-9$alpha]{0,61}[0-9$alpha])?\\.)+  # domain - RFC 1034
			[$alpha]([-0-9$alpha]{0,17}[$alpha])?              # top domain
		$)Dix
XX, $value);
		};

		if ($isEmail($email) === false) {
			throw new \InvalidArgumentException('Customer e-mail "' . $email . '" is not valid.');
		}
		$this->email = $email;
	}


	public function getPhone(): ?string
	{
		return $this->phone;
	}


	/**
	 * Normalize phone to basic format if pattern match.
	 *
	 * @param int $region use this prefix when number prefix does not exist
	 */
	public function setPhone(?string $phone, int $region = 420): void
	{
		if ($phone === null) {
			$this->phone = null;

			return;
		}

		$phone = (string) preg_replace('/\s+/', '', $phone); // remove spaces
		if (preg_match('/^([\+0-9]+)/', $phone, $trimUnexpected)) { // remove user notice and unexpected characters
			$phone = (string) $trimUnexpected[1];
		}
		if (preg_match('/^\+(4\d{2})(\d{3})(\d{3})(\d{3})$/', $phone, $prefixParser)) { // +420 xxx xxx xxx
			$phone = '+' . $prefixParser[1] . ' ' . $prefixParser[2] . ' ' . $prefixParser[3] . ' ' . $prefixParser[4];
		} elseif (preg_match('/^\+(4\d{2})(\d+)$/', $phone, $prefixSimpleParser)) { // +420 xxx
			$phone = '+' . $prefixSimpleParser[1] . ' ' . $prefixSimpleParser[2];
		} elseif (preg_match('/^(\d{3})(\d{3})(\d{3})$/', $phone, $regularParser)) { // numbers only
			$phone = '+' . $region . ' ' . $regularParser[1] . ' ' . $regularParser[2] . ' ' . $regularParser[3];
		} else {
			throw new \InvalidArgumentException('Phone number "' . $phone . '" for region "' . $region . '" does not exist.');
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
		if ($this->zip === null) {
			return null;
		}

		return (string) preg_replace('/^(\d{3})(\d{2})$/', '$1 $2', $this->zip);
	}


	public function setZip(?int $zip): void
	{
		if ($zip === null) {
			$this->zip = null;

			return;
		}
		if ($zip < 10000) {
			throw new \InvalidArgumentException('Zip "' . $zip . '" is too small.');
		}
		if ($zip > 99999) {
			throw new \InvalidArgumentException('Zip "' . $zip . '" is too big.');
		}

		$this->zip = $zip;
	}


	public function setZipString(string $zip): void
	{
		$this->setZip((int) preg_replace('/\D/', '', $zip));
	}


	public function getCarrierPickupPoint(): ?int
	{
		return $this->carrierPickupPoint;
	}


	public function setCarrierPickupPoint(?int $carrierPickupPoint): void
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


	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return get_object_vars($this);
	}
}
