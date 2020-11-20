<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.2/Deprecation-84171-AddingGeneralUtilitygetUrlRequestHeadersAsNon-associativeArrayAreDeprecated.html
 */
final class GeneralUtilityGetUrlRequestHeadersRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor GeneralUtility::getUrl() request headers in a associative way', [
            new CodeSample(<<<'PHP'
GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language: de-DE']);
PHP
                , <<<'PHP'
GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language' => 'de-DE']);
PHP
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'getUrl')) {
            return null;
        }

        if (! isset($node->args[2])) {
            return null;
        }

        $requestHeadersArgumentValue = $node->args[2]->value;

        $requestHeaders = $this->getValue($requestHeadersArgumentValue);

        if (! is_array($requestHeaders)) {
            return null;
        }

        $newHeaders = $this->buildHeaders($requestHeaders);

        if ([] === $newHeaders) {
            return null;
        }

        $newHeadersNode = $this->createArray($newHeaders);

        $node->args[2]->value = $newHeadersNode;

        return null;
    }

    private function buildHeaders(array $requestHeaders): array
    {
        $newHeaders = [];
        foreach ($requestHeaders as $requestHeader) {
            $parts = preg_split('#:[ \t]*#', $requestHeader, 2, PREG_SPLIT_NO_EMPTY);
            if (false === $parts) {
                continue;
            }

            if (2 !== count($parts)) {
                continue;
            }
            $key = &$parts[0];
            $value = &$parts[1];

            if (array_key_exists($key, $newHeaders)) {
                if (is_array($newHeaders[$key])) {
                    $newHeaders[$key][] = $value;
                } else {
                    $prevValue = &$newHeaders[$key];
                    $newHeaders[$key] = [$prevValue, $value];
                }
            } else {
                $newHeaders[$key] = $value;
            }
        }

        return $newHeaders;
    }
}
