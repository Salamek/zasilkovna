<?php

namespace Salamek\Zasilkovna\Model;

/**
 * User: Adam Schubert
 * Date: 3.8.17
 * Time: 1:00
 */
class ClaimAttributes
{
    /** @var int|null */
    protected $id;

    /** @var string */
    protected $number;

    /** @var string|null */
    protected $email;

    /** @var string */
    protected $phone;

    /** @var float */
    protected $value;

    /** @var string */
    protected $currency;

    /** @var string|null */
    protected $eshop;

    /** @var boolean|null */
    protected $sendLabelToEmail;

    /**
     * PacketAttributes constructor.
     * @param $number
     * @param $value
     * @param $currency
     * @param null $id
     * @param null $email
     * @param null $eshop
     * @param null $sendLabelToEmail
     */
    public function __construct($number, $value, $currency, $id = null, $email = null, $eshop = null, $sendLabelToEmail = null)
    {
        $this->number = $number;
        $this->value = $value;
        $this->currency = $currency;
        $this->id = $id;
        $this->email = $email;
        $this->eshop = $eshop;
        $this->sendLabelToEmail = $sendLabelToEmail;
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
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param string $eshop
     */
    public function setEshop($eshop)
    {
        $this->eshop = $eshop;
    }

    /**
     * @param boolean $sendLabelToEmail
     */
    public function setSendLabelToEmail($sendLabelToEmail)
    {
        $this->sendLabelToEmail = $sendLabelToEmail;
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
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
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
    public function isSendLabelToEmail()
    {
        return $this->sendLabelToEmail;
    }
}