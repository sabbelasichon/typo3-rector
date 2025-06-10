<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102621-MostTSFEMembersMarkedInternalOrRead-only.html
 * @see https://review.typo3.org/c/Packages/TYPO3.CMS/+/82158
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerGetContextRector\MigrateTypoScriptFrontendControllerGetContextRectorTest
 */
final class MigrateTypoScriptFrontendControllerGetContextRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `$GLOBALS[\'TSFE\']->getContext()`', [new CodeSample(
            <<<'CODE_SAMPLE'
$context = $GLOBALS['TSFE']->getContext();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$context = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            'makeInstance',
            [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Context\Context')]
        );
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->isGlobals($methodCall)
            && ! $this->isObjectType(
                $methodCall->var,
                new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
            )
        ) {
            return true;
        }

        return ! $this->isName($methodCall->name, 'getContext');
    }

    private function isGlobals(MethodCall $methodCall): bool
    {
        return $this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
