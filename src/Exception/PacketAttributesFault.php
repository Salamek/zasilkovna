<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Exception;


final class PacketAttributesFault extends \Exception
{
	/** @var string[] (name => fail) */
	private array $fails = [];


	/**
	 * @param object|object[] $fails
	 */
	public function __construct($fails)
	{
		foreach (((array) $fails) as $fail) {
			$this->fails[$fail->name] = (string) $fail->fault;
		}
		parent::__construct((string) $this);
	}


	public function __toString(): string
	{
		$return = '';
		foreach ($this->fails as $name => $fail) {
			$return .= $name . ': ' . $fail . ', ';
		}

		return trim($return, ', ');
	}
}
