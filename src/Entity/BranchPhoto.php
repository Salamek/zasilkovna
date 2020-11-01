<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Entity;


final class BranchPhoto
{
	private string $thumbnail;

	private string $normal;


	/**
	 * @param string[] $photos
	 */
	public function __construct(array $photos)
	{
		$this->thumbnail = $photos['thumbnail'] ?? '';
		$this->normal = $photos['normal'] ?? '';
	}


	public function getThumbnail(): string
	{
		return $this->thumbnail;
	}


	public function getNormal(): string
	{
		return $this->normal;
	}
}
