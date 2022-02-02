<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

final class ClaimAttributes implements IModel
{
    private ?int $id;

    private string $number;

    private ?string $email;

    private string $phone;

    private float $value;

    private string $currency;

    private ?string $eshop;

    private ?bool $sendLabelToEmail;


    public function __construct(
        string $number,
        float $value,
        string $currency,
        ?int $id = null,
        ?string $email = null,
        ?string $eshop = null,
        ?bool $sendLabelToEmail = null
    ) {
        $this->number = $number;
        $this->value = $value;
        $this->currency = $currency;
        $this->id = $id;
        $this->email = $email;
        $this->eshop = $eshop;
        $this->sendLabelToEmail = $sendLabelToEmail;
    }


    public function setSendLabelToEmail(bool $sendLabelToEmail): void
    {
        $this->sendLabelToEmail = $sendLabelToEmail;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getNumber(): string
    {
        return $this->number;
    }


    public function setNumber(string $number): void
    {
        $this->number = $number;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    public function getPhone(): string
    {
        return $this->phone;
    }


    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }


    public function getValue(): float
    {
        return $this->value;
    }


    public function setValue(float $value): void
    {
        $this->value = $value;
    }


    public function getCurrency(): string
    {
        return $this->currency;
    }


    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }


    public function getEshop(): string
    {
        return $this->eshop;
    }


    public function setEshop(string $eshop): void
    {
        $this->eshop = $eshop;
    }


    public function isSendLabelToEmail(): bool
    {
        return $this->sendLabelToEmail;
    }


    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
