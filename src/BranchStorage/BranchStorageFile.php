<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

/**
 * @internal
 */
final class BranchStorageFile implements IBranchStorage
{
	/** @var mixed[][]|null */
	private ?array $branchList = null;

	private string $filePath;


	public function __construct(?string $filePath = null)
	{
		$this->filePath = $filePath ?? sys_get_temp_dir() . '/' . md5(__CLASS__);
		if (\is_file($this->filePath)) {
			$this->branchList = \json_decode(file_get_contents($this->filePath), true);
		}
	}


	/**
	 * @return mixed[][]
	 */
	public function getBranchList(): array
	{
		if ($this->branchList === null) {
			throw new \RuntimeException('Branch list is empty.');
		}

		return $this->branchList;
	}


	public function setBranchList(array $branchList): void
	{
		$this->branchList = $branchList;
		file_put_contents($this->filePath, json_encode($branchList));
	}


	/**
	 * @return mixed[]
	 */
	public function find(int $id): ?array
	{
		foreach ($this->branchList ?? [] as $item) {
			if ($item['id'] === $id) {
				return $item;
			}
		}

		return null;
	}


	public function isStorageValid(): bool
	{
		return $this->branchList !== null;
	}
}
