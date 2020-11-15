<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Utility;

if (class_exists(GeneralUtility::class)) {
    return;
}

class GeneralUtility
{
    public static function getApplicationContext(): void
    {
    }

    /**
     * @return object
     */
    public static function makeInstance(string $class, ...$constructorArguments)
    {
        return new $class(...$constructorArguments);
    }

    public static function getUserObj(string $class): void
    {

    }

    public static function getIndpEnv(string $var): string
    {
        return 'foo';
    }

    public static function mkdir_deep(string $folder)
    {
        return 'foo';
    }

    public static function explodeUrl2Array($string, $multidim = null): string
    {
        return 'foo';
    }

    public static function logDeprecatedFunction(): string
    {
        return 'foo';
    }

    public static function logDeprecatedViewHelperAttribute(): string
    {
        return 'foo';
    }

    public static function deprecationLog(string $message): string
    {
        return $message ?? '';
    }

    public static function getDeprecationLogFileName(): string
    {
        return 'foo';
    }

    public static function makeInstanceService($serviceType, $serviceSubType = '', $excludeServiceKeys = []): string
    {
        return 'foo';
    }

    public static function trimExplode($delim, $string, $removeEmptyValues = false, $limit = 0): string
    {
        return 'foo';
    }

    public static function idnaEncode($value): string
    {
        return 'foo';
    }

    public static function isRunningOnCgiServerApi(): bool
    {
        return false;
    }

    public static function getUrl($url, $includeHeader = 0, $requestHeaders = null, &$report = null): void
    {

    }

    public static function IPv6Hex2Bin(string $hex): string
    {
        return '';
    }

    public static function IPv6Bin2Hex(string $bin): string
    {
        return '';
    }

    public static function compressIPv6(string $address): string
    {
        return '';
    }

    public static function milliseconds(): int
    {
        return 1;
    }

    public function verifyFilenameAgainstDenyPattern($filename): void
    {

    }

    public static function getFileAbsFileName($filename): void
    {

    }

    public static function generateRandomBytes(): string
    {
        return 'bytes';
    }

    public static function getRandomHexString(): string
    {
        return 'hex';
    }

    public static function requireOnce($requireFile): void
    {
    }

    public static function requireFile($requireFile): void
    {
    }

    public static function strtoupper($str): string
    {
        return 'FOO';
    }

    public static function strtolower($str): string
    {
        return 'foo';
    }

    public static function loadTCA(): void
    {

    }

    public static function int_from_ver($verNumberStr): int
    {
        return 1;
    }

    public static function array2xml_cs(array $array, $docTag = 'phparray', array $options = [], $charset = ''): string
    {
        // Set default charset unless explicitly specified
        $charset = $charset ?: 'utf-8';
        // Return XML:
        return '<?xml version="1.0" encoding="' . htmlspecialchars($charset) . '" standalone="yes" ?>' . LF . self::array2xml($array, '', 0, $docTag, 0, $options);
    }

    public static function array2xml(array $array, $NSprefix = '', $level = 0, $docTag = 'phparray', $spaceInd = 0, array $options = [], array $stackData = []): string
    {
        return 'xml';
    }

    public static function csvValues(array $row, $delim = ',', $quote = '"'): void
    {

    }
}
