<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Enum;


final class LabelDecomposition
{
	public const FULL = 1;

	public const QUARTER = 4;

	/** @var int[] */
	public static array $list = [
		self::FULL,
		self::QUARTER,
	];
}
