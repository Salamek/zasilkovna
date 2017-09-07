<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 3.8.17
 * Time: 1:19
 */

namespace Salamek\Zasilkovna\Model;

/**
 * Class DispatchOrder
 * @package Salamek\Zasilkovna\Model
 */
class DispatchOrder implements IModel
{
    /** @var string */
    private $goods;

    /** @var string */
    private $pdf;

    /**
     * DispatchOrder constructor.
     * @param string $goods
     * @param string $pdf
     */
    public function __construct($goods, $pdf)
    {
        $this->goods = $goods;
        $this->pdf = $pdf;
    }

    /**
     * @param mixed $goods
     */
    public function setGoods($goods)
    {
        $this->goods = $goods;
    }

    /**
     * @param mixed $pdf
     */
    public function setPdf($pdf)
    {
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
     * @return mixed
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}