<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

final class Size implements IModel
{
    private int $length;
    private int $width;
    private int $height;

    public function __construct(
        int $length,
        int $width,
        int $height
    ) {
        $this->setLength($length);
        $this->setWidth($width);
        $this->setHeight($height);
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
    }


    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
