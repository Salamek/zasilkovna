<?php

namespace Salamek\Zasilkovna\Model;

/**
 * User: Adam Schubert
 * Date: 3.8.17
 * Time: 1:01
 */
class PacketAttributes
{
    /** @var int */
    private $id;

    /** @var string */
    private $number;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var string */
    private $company;

    /** @var string */
    private $email;

    /** @var string */
    private $phone;

    /** @var int */
    private $addressId;

    /** @var string */
    private $currency;

    /** @var float */
    private $cod;

    /** @var float */
    private $value;

    /** @var float */
    private $weight;

    /** @var string */
    private $eshop;

    /** @var boolean */
    private $adultContent;

    /** @var string */
    private $street;

    /** @var string */
    private $houseNumber;

    /** @var string */
    private $city;

    /** @var string */
    private $zip;

    /** @var int */
    private $carrierPickupPoint;

    /** @var string */
    private $carrierService;

    /** @var DispatchOrder|null */
    private $dispatchOrder;

    /** @var string */
    private $customerBarcode;


    /**
     * PacketAttributes constructor.
     * @param $number
     * @param $name
     * @param $surname
     * @param $addressId
     * @param $id
     * @param $company
     * @param $email
     * @param $phone
     * @param $currency
     * @param $cod
     * @param $value
     * @param $weight
     * @param $eshop
     * @param $adultContent
     * @param $street
     * @param $houseNumber
     * @param $city
     * @param $zip
     * @param $carrierPickupPoint
     * @param $carrierService
     * @param $dispatchOrder
     * @param $customerBarcode
     */
    public function __construct(
        $number,
        $name,
        $surname,
        $value,
        $addressId,
        $id = null,
        $company = null,
        $email = null,
        $phone = null,
        $currency = null,
        $cod = null,
        $weight = null,
        $eshop = null,
        $adultContent = null,
        $street = null,
        $houseNumber = null,
        $city = null,
        $zip = null,
        $carrierPickupPoint = null,
        $carrierService = null,
        $dispatchOrder = null,
        $customerBarcode = null
    )
    {
        $this->number = $number;
        $this->name = $name;
        $this->surname = $surname;
        $this->addressId = $addressId;

        $this->id = $id;
        $this->company = $company;
        $this->email = $email;
        $this->phone = $phone;
        $this->currency = $currency;
        $this->cod = $cod;
        $this->value = $value;
        $this->weight = $weight;
        $this->eshop = $eshop;
        $this->adultContent = $adultContent;
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->city = $city;
        $this->zip = $zip;
        $this->carrierPickupPoint = $carrierPickupPoint;
        $this->carrierService = $carrierService;
        $this->dispatchOrder = $dispatchOrder;
        $this->customerBarcode = $customerBarcode;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param int $addressId
     */
    public function setAddressId($addressId)
    {
        $this->addressId = $addressId;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param float $cod
     */
    public function setCod($cod)
    {
        $this->cod = $cod;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @param string $eshop
     */
    public function setEshop($eshop)
    {
        $this->eshop = $eshop;
    }

    /**
     * @param boolean $adultContent
     */
    public function setAdultContent($adultContent)
    {
        $this->adultContent = $adultContent;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @param string $houseNumber
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @param int $carrierPickupPoint
     */
    public function setCarrierPickupPoint($carrierPickupPoint)
    {
        $this->carrierPickupPoint = $carrierPickupPoint;
    }

    /**
     * @param string $carrierService
     */
    public function setCarrierService($carrierService)
    {
        $this->carrierService = $carrierService;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return int
     */
    public function getAddressId()
    {
        return $this->addressId;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getEshop()
    {
        return $this->eshop;
    }

    /**
     * @return boolean
     */
    public function isAdultContent()
    {
        return $this->adultContent;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return int
     */
    public function getCarrierPickupPoint()
    {
        return $this->carrierPickupPoint;
    }

    /**
     * @return string
     */
    public function getCarrierService()
    {
        return $this->carrierService;
    }

    /**
     * @return null|DispatchOrder
     */
    public function getDispatchOrder()
    {
        return $this->dispatchOrder;
    }

    /**
     * @return string
     */
    public function getCustomerBarcode()
    {
        return $this->customerBarcode;
    }

}