<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Exception;


final class PacketAttributesFault extends \Exception
{
	/** @var string[] (name => fail) */
	private array $fails = [];

	public function __construct(array $fails)
	{
		if($fails['name'] ?? true == false) {
			$this->fails = [$fails]; // One fail
		} else {
			$this->fails = $fails; // Many fails
		}

		parent::__construct((string) $this);
	}

	public function __toString(): string
	{
		$return = '';
		foreach ($this->fails as $fail)	{
			$return .= $fail['name'] . ': ' . $fail['fault'] . ', ';
		}
		return trim($return, ', ');
	}
}
