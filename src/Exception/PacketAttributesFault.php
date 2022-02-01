<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Exception;


final class PacketAttributesFault extends \Exception
{
	/** @var mixed[] (name => fail) */
	private array $fails = [];

	public function __construct(mixed $fails)
	{
		// Normalize schema
		if (isset($fails['name'])) {
			$this->fails = [$fails]; // One fail to array
		} else {
			$this->fails = $fails; // Many fails keep same
		}

		parent::__construct((string)$this);
	}

	public function __toString(): string
	{
		$return = '';
		foreach ($this->fails as $fail) {
			$return .= $fail['name'] . ': ' . $fail['fault'] . ', ';
		}
		return trim($return, ', ');
	}
}
