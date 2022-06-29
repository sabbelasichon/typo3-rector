<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

abstract class AbstractGlobalConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var array<string, string>
     */
    public const IENV_MAPPING_NORMALIZED = [
        'SCRIPT_NAME' => 'getScriptName',
        'SCRIPT_FILENAME' => 'getScriptFilename',
        'REQUEST_URI' => 'getRequestUri',
        'TYPO3_REV_PROXY' => 'isBehindReverseProxy',
        'REMOTE_ADDR' => 'getRemoteAddress',
        'HTTP_HOST' => 'getHttpHost',
        'TYPO3_DOCUMENT_ROOT' => 'getDocumentRoot',
        'TYPO3_HOST_ONLY' => 'getRequestHostOnly',
        'TYPO3_PORT' => 'getRequestPort',
        'TYPO3_REQUEST_HOST' => 'getRequestHost',
        'TYPO3_REQUEST_URL' => 'getRequestUrl',
        'TYPO3_REQUEST_SCRIPT' => 'getRequestScript',
        'TYPO3_REQUEST_DIR' => 'getRequestDir',
        'TYPO3_SITE_URL' => 'getSiteUrl',
        'TYPO3_SITE_PATH' => 'getSitePath',
        'TYPO3_SITE_SCRIPT' => 'getSiteScript',
        'TYPO3_SSL' => 'isHttps',
        'HTTPS' => 'isHttps',
        'PATH_INFO' => 'getScriptName',
    ];

    /**
     * @var string[]
     */
    public const IENV_KEEP_SERVER_PARAMS = [
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'HTTP_ACCEPT_ENCODING',
        'HTTP_ACCEPT_LANGUAGE',
        'REMOTE_HOST',
        'QUERY_STRING',
    ];

    /**
     * @var array<string, string>
     */
    public const USER_PROPERTY_MAPPING = [
        'uid' => 'userId',
    ];

    protected function refactorTsfe(string $property, string $operator, string $value): string
    {
        if (\str_starts_with($property, 'page')) {
            $parameters = ArrayUtility::trimExplode('|', $property, true);
            return sprintf('page["%s"] %s %s', $parameters[1], self::OPERATOR_MAPPING[$operator], $value);
        }

        return sprintf('getTSFE().%s %s %s', $property, self::OPERATOR_MAPPING[$operator], $value);
    }

    protected function createEnvCondition(string $property, string $operator, string $value): string
    {
        return sprintf('getenv("%s") %s "%s"', $property, self::OPERATOR_MAPPING[$operator], $value);
    }

    protected function createIndependentCondition(string $property, string $operator, string $value): string
    {
        $condition = 'ERROR not implemented';
        if (array_key_exists($property, self::IENV_MAPPING_NORMALIZED)) {
            if (\str_contains($value, '*')) {
                return sprintf(
                    'like(request.getNormalizedParams().%s(), "%s")',
                    self::IENV_MAPPING_NORMALIZED[$property],
                    $value
                );
            }

            if (\str_starts_with(self::IENV_MAPPING_NORMALIZED[$property], 'get')) {
                $condition = sprintf(
                    'request.getNormalizedParams().%s() %s "%s"',
                    self::IENV_MAPPING_NORMALIZED[$property],
                    self::OPERATOR_MAPPING[$operator],
                    $value
                );
            } else {
                $condition = sprintf(
                    'request.getNormalizedParams().%s()',
                    self::IENV_MAPPING_NORMALIZED[$property]
                );
            }
        }

        if (in_array($property, self::IENV_KEEP_SERVER_PARAMS, true)) {
            if (\str_contains($value, '*')) {
                return sprintf('like(request.getServerParams()[\'%s\'], "%s")', $property, $value);
            }

            $condition = sprintf(
                'request.getServerParams()[\'%s\'] %s "%s"',
                $property,
                self::OPERATOR_MAPPING[$operator],
                $value
            );
        }

        return $condition;
    }
}
