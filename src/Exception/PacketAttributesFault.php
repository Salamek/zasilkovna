<?php

namespace Salamek\Zasilkovna\Exception;

use function is_array;

class PacketAttributesFault extends \Exception
{

	private $fails;

	public function __construct($fails)
	{

		$fails = !is_array($fails) ? [$fails] : $fails;

		foreach ($fails as $fail) {
			$this->fails[$fail->name] = $fail->fault;
		}
		parent::__construct($this->__toString());
	}

	public function __toString()
	{
		$string = '';
		foreach ($this->fails as $name => $fail) {
			$string .= $name . ': ' . $fail . ', ';
		}

		return trim($string, ', ');
	}
}
