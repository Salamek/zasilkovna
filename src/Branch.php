<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;


use Salamek\Zasilkovna\Entity\Branch as BranchEntity;
use Salamek\Zasilkovna\Model\IBranchStorage;

final class Branch
{
	private IBranchStorage $branchStorage;

	private string $jsonEndpoint;


	public function __construct(string $apiKey, IBranchStorage $branchStorage)
	{
		if (trim($apiKey) === '') {
			throw new \RuntimeException('API key can not be empty.');
		}
		$this->branchStorage = $branchStorage;
		$this->jsonEndpoint = 'https://www.zasilkovna.cz/api/v3/' . $apiKey . '/branch.json';
		$this->initializeStorage();
	}


	public function initializeStorage(bool $force = false): void
	{
		if ($force || !$this->branchStorage->isStorageValid()) {
			if (!($result = file_get_contents($this->jsonEndpoint))) {
				throw new \RuntimeException('Failed to open JSON endpoint');
			}
			if (!($data = \json_decode($result, true)) || !array_key_exists('data', $data)) {
				throw new \RuntimeException('Failed to decode JSON');
			}

			$this->branchStorage->setBranchList($data['data']);
		}
	}


	/**
	 * @return BranchEntity[]
	 */
	public function getBranchList(): array
	{
		$return = [];
		foreach ($this->branchStorage->getBranchList() as $branch) {
			$return[] = new BranchEntity($branch);
		}

		return $return;
	}


	public function find(int $id): ?BranchEntity
	{
		if (($branch = $this->branchStorage->find($id)) === null) {
			return null;
		}

		return new BranchEntity($branch);
	}
}
