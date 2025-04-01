<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-100405-PropertyTypoScriptFrontendController-type.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateTypoScriptFrontendControllerTypeRector\MigrateTypoScriptFrontendControllerTypeRectorTest
 */
final class MigrateTypoScriptFrontendControllerTypeRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(Typo3NodeResolver $typo3NodeResolver, Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate TypoScriptFrontendController->type', [new CodeSample(
            <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->type;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->getPageArguments()->getPageType();
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isName($node->name, 'type')) {
            return null;
        }

        if ($this->isGlobals($node)) {
            return $this->createGetPageTypeMethodCall($this->createTSFEGetPageArgumentsMethodCall());
        }

        return $this->createGetPageTypeMethodCall($this->nodeFactory->createMethodCall($node->var, 'getPageArguments'));
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        return ! $this->isGlobals($propertyFetch) && ! $this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }

    private function isGlobals(PropertyFetch $propertyFetch): bool
    {
        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    private function createTSFEGetPageArgumentsMethodCall(): MethodCall
    {
        return $this->nodeFactory->createMethodCall($this->typo3GlobalsFactory->create('TSFE'), 'getPageArguments');
    }

    private function createGetPageTypeMethodCall(MethodCall $methodCall): MethodCall
    {
        return $this->nodeFactory->createMethodCall($methodCall, 'getPageType');
    }
}
