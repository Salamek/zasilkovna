<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;

use Salamek\Zasilkovna\Entity\IBranch;
use Salamek\Zasilkovna\Entity\ZasilkovnaBranch;
use Salamek\Zasilkovna\Model\BranchStorageFile;
use Salamek\Zasilkovna\Model\IBranchStorage;

final class Branch
{
    private IBranchStorage $branchStorage;

    private string $jsonEndpoint;

    private ?string $hydrateToEntity = null;

    private bool $initialized = false;


    public function __construct(string $apiKey, ?IBranchStorage $branchStorage = null)
    {
        if (\trim($apiKey) === '') {
            throw new \RuntimeException('API key can not be empty.');
        }
        $this->branchStorage = $branchStorage ?? new BranchStorageFile();
        $this->jsonEndpoint = 'https://www.zasilkovna.cz/api/v4/' . $apiKey . '/branch.json';
    }


    public function initializeStorage(bool $force = false): void
    {
        if ($this->initialized === true && $force === false) {
            return;
        }
        if ($force || !$this->branchStorage->isStorageValid()) {
            if (!($result = file_get_contents($this->jsonEndpoint))) {
                throw new \RuntimeException('Failed to open JSON endpoint');
            }
            if (!($data = \json_decode($result, true)) || !array_key_exists('data', $data)) {
                throw new \RuntimeException('Failed to decode JSON');
            }

            $this->branchStorage->setBranchList($data['data']);
        }
        $this->initialized = true;
    }


    /**
     * @return IBranch[]
     */
    public function getBranchList(): array
    {
        $this->initializeStorage();
        $entity = $this->getHydrateToEntity();
        $return = [];
        foreach ($this->branchStorage->getBranchList() as $branch) {
            $return[] = new $entity($branch);
        }

        return $return;
    }


    public function find(int $id): ?IBranch
    {
        $this->initializeStorage();
        if (($branch = $this->branchStorage->find($id)) === null) {
            return null;
        }

        $entity = $this->getHydrateToEntity();

        return new $entity($branch);
    }


    /**
     * This method finds the nearest branch of the courier
     * and returns them as a list sorted from the nearest to the farthest.
     * The number of results may vary depending on the number of branches in the area.
     * When searching for branches, the list of candidates from the area is first filtered,
     * and the individual branches are sorted in it.
     *
     * @return IBranch[]
     */
    public function findNearest(float $latitude, float $longitude, float $kilometersAround = 5, int $limit = 100): array
    {
        $this->initializeStorage();
        $candidates = [];
        $candidateArea = ($kilometersAround > 100 ? 100 : $kilometersAround) * 0.01;
        foreach ($this->getBranchList() as $candidateBranch) {
            if (
                abs($candidateBranch->getLatitude() - $latitude) < $candidateArea
                && abs($candidateBranch->getLongitude() - $longitude) < $candidateArea
            ) {
                $candidates[] = [
                    'branch' => $candidateBranch,
                    'distance' => $candidateBranch->getDistanceFrom($latitude, $longitude),
                ];
            }
        }
        usort($candidates, fn(array $a, array $b): int => $a['distance'] > $b['distance'] ? 1 : -1);

        return array_slice(array_map(fn(array $item) => $item['branch'], $candidates), 0, $limit);
    }


    public function getHydrateToEntity(): string
    {
        return $this->hydrateToEntity ?? ZasilkovnaBranch::class;
    }


    public function setHydrateToEntity(?string $hydrateToEntity): void
    {
        $this->hydrateToEntity = $hydrateToEntity;
    }
}
