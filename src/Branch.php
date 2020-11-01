<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;


use Salamek\Zasilkovna\Model\IBranchStorage;

final class Branch
{
	private IBranchStorage $branchStorage;

	private string $jsonEndpoint;


	public function __construct(string $apiKey, IBranchStorage $branchStorage)
	{
		$this->branchStorage = $branchStorage;
		$this->jsonEndpoint = 'https://www.zasilkovna.cz/api/v3/' . $apiKey . '/branch.json';
		$this->initializeStorage();
	}


	public function initializeStorage(bool $force = false): void
	{
		if ($force || !$this->branchStorage->isStorageValid()) {
			$result = file_get_contents($this->jsonEndpoint);
			if (!$result) {
				throw new \RuntimeException('Failed to open JSON endpoint');
			}

			$data = \json_decode($result, true);
			if (!$data || !array_key_exists('data', $data)) {
				throw new \RuntimeException('Failed to decode JSON');
			}

			$this->branchStorage->setBranchList($data['data']);
		}
	}


	public function getBranchList(): array
	{
		return $this->branchStorage->getBranchList();
	}


	public function find(int $id): array
	{
		return $this->branchStorage->find($id);
	}
}
