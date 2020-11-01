<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;


interface IModel
{
	/**
	 * @return mixed[]
	 */
	public function toArray(): array;
}
