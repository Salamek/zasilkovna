<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Enum;

final class LabelPosition
{
    public const TOP_LEFT = 1;

    public const TOP_RIGHT = 2;

    public const BOTTOM_LEFT = 3;

    public const BOTTOM_RIGHT = 4;

    /** @var int[] */
    public static array $list = [
        self::TOP_LEFT,
        self::TOP_RIGHT,
        self::BOTTOM_LEFT,
        self::BOTTOM_RIGHT,
    ];
}
