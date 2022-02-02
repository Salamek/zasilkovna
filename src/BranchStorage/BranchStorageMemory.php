<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

/**
 * @internal
 */
final class BranchStorageMemory implements IBranchStorage
{
    /** @var mixed[][]|null */
    private ?array $branchList = null;


    public function __construct()
    {
        user_error('BranchStorageMemory is extremely slow and SHOULD NOT be used in production!', E_USER_NOTICE);
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
    }


    /**
     * @return mixed[]
     */
    public function find(int $id): ?array
    {
        foreach ($this->branchList ?? [] as $item) {
            if (((int) $item['id']) === $id) {
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
