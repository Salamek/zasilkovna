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
        $this->statusId = (int) $photos['statusId'] ?? -1;
        $this->description = $photos['description'] ?? '';
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
