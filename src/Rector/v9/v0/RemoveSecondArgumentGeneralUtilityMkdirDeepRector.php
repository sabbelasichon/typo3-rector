<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82702-SecondArgumentOfGeneralUtilitymkdir_deep.html
 */
final class RemoveSecondArgumentGeneralUtilityMkdirDeepRector extends AbstractRector
{
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
        if (! $this->isName($node->name, 'mkdir_deep')) {
            return null;
        }
        $arguments = $node->args;
        if (count($arguments) <= 1) {
            return null;
        }
        $concat = new Concat($node->args[0]->value, $node->args[1]->value);
        return $this->createStaticCall(GeneralUtility::class, 'mkdir_deep', [$concat]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove second argument of GeneralUtility::mkdir_deep()', [new CodeSample(<<<'PHP'
GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/', 'myfolder');
PHP
, <<<'PHP'
GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/' . 'myfolder');
PHP
)]);
    }
}
