<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;


final class BranchStorageMemory implements IBranchStorage
{
	private array $branchList;


	public function __construct()
	{
		user_error('BranchStorageMemory is extremely slow and SHOULD NOT be used in production!', E_USER_NOTICE);
	}


	public function getBranchList(): array
	{
		return $this->branchList;
	}


	public function setBranchList(array $branchList): void
	{
		$this->branchList = $branchList;
	}


	public function find(int $id): ?array
	{
		foreach ($this->branchList as $item) {
			if ($item['id'] === $id) {
				return $item;
			}
		}

		return null;
	}


	public function isStorageValid(): bool
	{
		return !empty($this->branchList);
	}
}
