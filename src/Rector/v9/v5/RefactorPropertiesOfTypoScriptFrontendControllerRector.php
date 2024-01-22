<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.5/Deprecation-86047-TSFEPropertiesMethodsAndChangeVisibility.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v5\RefactorPropertiesOfTypoScriptFrontendControllerRector\RefactorPropertiesOfTypoScriptFrontendControllerRectorTest
 */
final class RefactorPropertiesOfTypoScriptFrontendControllerRector extends AbstractRector
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Return_::class, Assign::class];
    }

    /**
     * @param Node\Stmt\Return_|Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        $propertyFetch = $node->expr;

        if (! $propertyFetch instanceof PropertyFetch) {
            return null;
        }

        if (! $this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        )
            && ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
                $propertyFetch,
                Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
            )) {
            return null;
        }

        // Check if we have an assigment to the property, if so do not change it
        if ($node instanceof Assign && $node->var instanceof PropertyFetch) {
            return null;
        }

        if (! $this->isNames(
            $propertyFetch->name,
            ['ADMCMD_preview_BEUSER_uid', 'workspacePreview', 'loginAllowedInBranch']
        )) {
            return null;
        }

        if ($this->isName($propertyFetch->name, 'loginAllowedInBranch')) {
            $node->expr = $this->nodeFactory->createMethodCall($propertyFetch->var, 'checkIfLoginAllowedInBranch');

            return $node;
        }

        $contextInstanceNode = $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            'makeInstance',
            [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Context\Context')]
        );

        if ($this->isName($propertyFetch->name, 'ADMCMD_preview_BEUSER_uid')) {
            $node->expr = $this->nodeFactory->createMethodCall(
                $contextInstanceNode,
                'getPropertyFromAspect',
                ['backend.user', 'id', 0]
            );

            return $node;
        }

        $node->expr = $this->nodeFactory->createMethodCall(
            $contextInstanceNode,
            'getPropertyFromAspect',
            ['workspace', 'id', 0]
        );

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor some properties of TypoScriptFrontendController', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$previewBeUserUid = $GLOBALS['TSFE']->ADMCMD_preview_BEUSER_uid;
$workspacePreview = $GLOBALS['TSFE']->workspacePreview;
$loginAllowedInBranch = $GLOBALS['TSFE']->loginAllowedInBranch;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;

$previewBeUserUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'id', 0);
$workspacePreview = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);
$loginAllowedInBranch = $GLOBALS['TSFE']->checkIfLoginAllowedInBranch();
CODE_SAMPLE
            ),
        ]);
    }
}
