<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Model;

interface IBranchStorage
{
    /**
     * @return mixed[][]
     */
    public function getBranchList(): array;

    /**
     * @return mixed[]
     */
    public function find(int $id): ?array;

    /**
     * @param mixed[] $branchList
     */
    public function setBranchList(array $branchList): void;

    public function isStorageValid(): bool;
}
