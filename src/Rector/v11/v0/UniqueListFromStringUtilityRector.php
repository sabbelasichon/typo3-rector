<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.0/Deprecation-92607-DeprecatedGeneralUtilityuniqueList.html
 */
final class UniqueListFromStringUtilityRector extends AbstractRector
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'uniqueList')) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(StringUtility::class, 'uniqueList', [$node->args[0]]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use StringUtility::uniqueList() instead of GeneralUtility::uniqueList', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::uniqueList('1,2,2,3');
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\StringUtility;
StringUtility::uniqueList('1,2,2,3');
CODE_SAMPLE
        ),
        ]);
    }
}
