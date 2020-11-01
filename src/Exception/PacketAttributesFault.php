<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Exception;


final class PacketAttributesFault extends \Exception
{
	private array $fails = [];


	public function __construct($fails)
	{
		$fails = !\is_array($fails) ? [$fails] : $fails;
		foreach ($fails as $fail) {
			$this->fails[$fail->name] = $fail->fault;
		}
		parent::__construct((string) $this);
	}


	public function __toString(): string
	{
		$string = '';
		foreach ($this->fails as $name => $fail) {
			$string .= $name . ': ' . $fail . ', ';
		}

		return trim($string, ', ');
	}
}
