<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Entity;

final class BranchStatus
{
    private int $statusId;

    private string $description;

    /**
     * @param array $status
     */
    public function __construct(array $status)
    {
        $this->statusId = (int) $status['statusId'] ?? -1;
        $this->description = $status['description'] ?? '';
    }


    public function getStatusId(): int
    {
        return $this->statusId;
    }


    public function getDescription(): string
    {
        return $this->description;
    }
}
