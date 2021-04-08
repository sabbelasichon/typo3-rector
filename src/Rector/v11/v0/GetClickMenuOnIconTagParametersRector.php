<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.0/Deprecation-92583-DeprecateLastArgumentsOfWrapClickMenuOnIcon.html
 */
final class GetClickMenuOnIconTagParametersRector extends AbstractRector
{
    /**
<<<<<<< HEAD
     * @return array<class-string<\PhpParser\Node>>
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, BackendUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'wrapClickMenuOnIcon')) {
            return null;
        }

        if (! count($node->args) > 3) {
            return null;
        }

        $returnTagParameters = isset($node->args[6]) ? $this->valueResolver->getValue($node->args[6]->value) : false;

        if (null === $returnTagParameters) {
            return null;
        }

        if (false === $returnTagParameters) {
            unset($node->args[3], $node->args[4], $node->args[5], $node->args[6]);
            return $node;
        }

        return $this->nodeFactory->createStaticCall(BackendUtility::class, 'getClickMenuOnIconTagParameters', [
            $node->args[0],
            $node->args[1],
            $node->args[2],
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use BackendUtility::getClickMenuOnIconTagParameters() instead BackendUtility::wrapClickMenuOnIcon() if needed',
            [new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;
$returnTagParameters = true;
BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', '', '', $returnTagParameters);
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;
$returnTagParameters = true;
BackendUtility::getClickMenuOnIconTagParameters('pages', 1, 'foo');
CODE_SAMPLE
        )]);
    }
}
