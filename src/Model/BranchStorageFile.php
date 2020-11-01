<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

final class BranchStorageFile implements IBranchStorage
{
	private array $branchList;

	private string $filePath;

	private bool $storageValid = false;


	public function __construct(?string $filePath = null)
	{
		$this->filePath = $filePath ?? sys_get_temp_dir() . '/' . md5(__CLASS__);
		if (\is_file($this->filePath)) {
			$this->branchList = \json_decode(file_get_contents($this->filePath), true);
			if (!empty($this->branchList)) {
				$this->storageValid = true;
			}
		}
	}


	public function getBranchList(): array
	{
		return $this->branchList;
	}


	public function setBranchList(array $branchList): void
	{
		$this->branchList = $branchList;
		file_put_contents($this->filePath, json_encode($branchList));
		$this->storageValid = true;
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
		return $this->storageValid;
	}
}
