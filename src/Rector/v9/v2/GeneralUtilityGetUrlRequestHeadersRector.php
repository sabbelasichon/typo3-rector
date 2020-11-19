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
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }
        if (!$this->isName($node->name, 'getUrl')) {
            return null;
        }

        $args = $node->args;
        if (isset($args[2])) {
            $newHeaders = [];
            $oldHeaders = $this->getValue($args[2]->value);
            foreach ($oldHeaders as $header) {
                $singleHeader = explode(':', $header);
                $newHeaders[trim($singleHeader[0])] = trim($singleHeader[1]);
            }
            // Replace headers argument
            unset($args[2]);
            $args[2] = $this->createArg($newHeaders);

        }
        return $this->createStaticCall(GeneralUtility::class, 'getUrl', $args);
    }
}
