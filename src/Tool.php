<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;

class Tool
{
    /**
     * Convert mixed input into string
     * @param mixed $input
     * @return string
     */
    public static function convertToString(mixed $input): string
    {
        if (\is_array($input)) {
            return \implode(', ', $input);
        }

        return (string) $input;
    }
}
