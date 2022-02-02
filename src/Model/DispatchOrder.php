<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

class DispatchOrder implements IModel
{
    /** @var mixed */
    private $goods;

    /** @var mixed */
    private $pdf;


    /**
     * @param mixed $goods
     * @param mixed $pdf
     */
    public function __construct($goods, $pdf)
    {
        $this->goods = $goods;
        $this->pdf = $pdf;
    }


    /**
     * @return mixed
     */
    public function getGoods()
    {
        return $this->goods;
    }


    /**
     * @param mixed $goods
     */
    public function setGoods($goods): void
    {
        $this->goods = $goods;
    }


    /**
     * @return mixed
     */
    public function getPdf()
    {
        return $this->pdf;
    }


    /**
     * @param mixed $pdf
     */
    public function setPdf($pdf): void
    {
        $this->pdf = $pdf;
    }


    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
