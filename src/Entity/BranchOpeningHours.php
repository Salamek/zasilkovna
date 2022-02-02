<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna\Entity;

use Salamek\Zasilkovna\Tool;

final class BranchOpeningHours
{
    private string $compactShort;

    private string $compactLong;

    private string $tableLong;

    /** @var string[] */
    private array $regular;

    /** @var mixed[] */
    private array $exceptions;


    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->compactShort = Tool::convertToString($data['compactShort'] ?? '');
        $this->compactLong = Tool::convertToString($data['compactLong'] ?? '');
        $this->tableLong = Tool::convertToString($data['tableLong'] ?? '');
        $this->regular = (array) ($data['regular'] ?? []);
        $this->exceptions = (array) ($data['exceptions'] ?? []);
    }


    public function getCompactShort(): string
    {
        return $this->compactShort;
    }


    public function getCompactLong(): string
    {
        return $this->compactLong;
    }


    public function getTableLong(): string
    {
        return $this->tableLong;
    }


    /**
     * @return string[]
     */
    public function getRegular(): array
    {
        return $this->regular;
    }


    /**
     * @return mixed[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
