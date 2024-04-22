<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

final class ArrayUtility
{
    /**
     * @return string[]
     */
    public static function trimExplode(
        string $delimiter,
        string $string,
        bool $removeEmptyValues = false,
        int $limit = 0
    ): array {
        if ($delimiter === '') {
            throw new \InvalidArgumentException('Please define a correct delimiter');
        }

        $result = explode($delimiter, $string);

        if ($removeEmptyValues) {
            $temp = [];
            foreach ($result as $value) {
                if (trim($value) !== '') {
                    $temp[] = $value;
                }
            }

            $result = $temp;
        }

        if ($limit > 0 && count($result) > $limit) {
            $lastElements = array_splice($result, $limit - 1);
            $result[] = implode($delimiter, $lastElements);
        } elseif ($limit < 0) {
            $result = array_slice($result, 0, $limit);
        }

        return array_map('trim', $result);
    }

    /**
     * Exports an array as string.
     * Similar to var_export(), but representation follows the PSR-2 and TYPO3 core CGL.
     *
     * See unit tests for detailed examples
     *
     * @param array<int|string, mixed> $array Array to export
     * @param int $level Internal level used for recursion, do *not* set from outside!
     * @return string String representation of array
     */
    public static function arrayExport(array $array = [], int $level = 0): string
    {
        $lines = "[\n";
        ++$level;
        $writeKeyIndex = false;
        $expectedKeyIndex = 0;
        foreach (array_keys($array) as $key) {
            if ($key === $expectedKeyIndex) {
                ++$expectedKeyIndex;
            } else {
                // Found a non-integer or non-consecutive key, so we can break here
                $writeKeyIndex = true;
                break;
            }
        }

        foreach ($array as $key => $value) {
            // Indention
            $lines .= str_repeat('    ', $level);
            if ($writeKeyIndex) {
                // Numeric / string keys
                $lines .= is_int($key) ? $key . ' => ' : "'" . $key . "' => ";
            }

            if (is_array($value)) {
                if ($value !== []) {
                    $lines .= self::arrayExport($value, $level);
                } else {
                    $lines .= "[],\n";
                }
            } elseif (is_int($value) || is_float($value)) {
                $lines .= $value . ",\n";
            } elseif ($value === null) {
                $lines .= "null,\n";
            } elseif (is_bool($value)) {
                $lines .= $value ? 'true' : 'false';
                $lines .= ",\n";
            } elseif (is_string($value)) {
                // Quote \ to \\
                // Quote ' to \'
                $stringContent = str_replace(['\\', "'"], ['\\\\', '\\\''], $value);
                $lines .= "'" . $stringContent . "',\n";
            } else {
                throw new \RuntimeException('Objects are not supported', 1342294987);
            }
        }

        return $lines . (str_repeat('    ', $level - 1) . ']' . ($level - 1 === 0 ? '' : ",\n"));
    }
}
