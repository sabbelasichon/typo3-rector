<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.3/Deprecation-76804-DeprecateGeneralUtilitystrtoupperStrtolower.html
 */
final class GeneralUtilityToUpperAndLowerRector extends AbstractRector
{
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

        if (! $this->isNames($node->name, ['strtoupper', 'strtolower'])) {
            return null;
        }

        $funcCall = 'mb_strtolower';
        if ($this->isName($node->name, 'strtoupper')) {
            $funcCall = 'mb_strtoupper';
        }

        return $this->createFuncCall($funcCall, [$node->args[0], $this->createArg('utf-8')]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use mb_strtolower and mb_strtoupper', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$toUpper = GeneralUtility::strtoupper('foo');
$toLower = GeneralUtility::strtolower('FOO');
PHP
                , <<<'PHP'
$toUpper = mb_strtoupper('foo', 'utf-8');
$toLower = mb_strtolower('FOO', 'utf-8');
PHP
            ),
        ]);
    }
}
