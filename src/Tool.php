<?php

declare(strict_types=1);

namespace Salamek\Zasilkovna;

use Salamek\Zasilkovna\Model\IModel;

class Tool
{
    /**
     * Convert mixed input into string
     * @param mixed $input
     * @return string
     */
    public static function convertToString(/*mixed */$input): string
    {
        if (\is_array($input)) {
            return \implode(', ', $input);
        }

        return (string) $input;
    }

    /**
     * Normalizes phone number
     * @param string $phone
     * @param int $region
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function normalizePhoneNumber(string $phone, int $region = 420): string
    {
        $phone = (string) preg_replace('/\s+/', '', $phone); // remove spaces
        if (preg_match('/^([\+0-9]+)/', $phone, $trimUnexpected)) { // remove user notice and unexpected characters
            $phone = (string) $trimUnexpected[1];
        }
        if (preg_match('/^\+(4\d{2})(\d{3})(\d{3})(\d{3})$/', $phone, $prefixParser)) { // +420 xxx xxx xxx
            $phone = '+' . $prefixParser[1] . ' ' . $prefixParser[2] . ' ' . $prefixParser[3] . ' ' . $prefixParser[4];
        } elseif (preg_match('/^\+(4\d{2})(\d+)$/', $phone, $prefixSimpleParser)) { // +420 xxx
            $phone = '+' . $prefixSimpleParser[1] . ' ' . $prefixSimpleParser[2];
        } elseif (preg_match('/^(\d{3})(\d{3})(\d{3})$/', $phone, $regularParser)) { // numbers only
            $phone = '+' . $region . ' ' . $regularParser[1] . ' ' . $regularParser[2] . ' ' . $regularParser[3];
        } else {
            $message = sprintf(
                'Phone number "%s" for region "%s" does not exist.',
                $phone,
                $region
            );
            throw new \InvalidArgumentException($message);
        }

        return $phone;
    }

    public static function resolveNestedVars(array $vars): array
    {
        $ret = [];
        foreach ($vars as $key => $value) {
            if (is_object($value) === false) {
                $ret[$key] = $value;
                continue;
            }

            if ($value instanceof IModel) {
                $ret[$key] = $value->toArray();
                continue;
            }

            throw new \InvalidArgumentException(sprintf('Property %s has unsupported type of value.', $key));
        }

        return $ret;
    }
}
