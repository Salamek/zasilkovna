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
class DispatchOrder
{
    /** @var string */
    protected $goods;

    /** @var string */
    protected $pdf;

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
}