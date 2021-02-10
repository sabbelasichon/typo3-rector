<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73516-VariousGeneralUtilityMethods.html
 */
final class GetFileAbsFileNameRemoveDeprecatedArgumentsRector extends AbstractRector
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'getFileAbsFileName')) {
            return null;
        }

        if (1 === count($node->args)) {
            return null;
        }

        $node->args = [$node->args[0]];

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove second and third argument of GeneralUtility::getFileAbsFileName()', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::getFileAbsFileName('foo.txt', false, true);
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::getFileAbsFileName('foo.txt');
PHP
            ),
        ]);
    }
}
