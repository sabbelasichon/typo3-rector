<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102621-MostTSFEMembersMarkedInternalOrRead-only.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRectorTest
 */
final class MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, string>
     */
    private const PROPERTY_TO_METHOD_MAP = [
        'id' => 'getId',
        'rootLine' => 'getRootLine',
        'page' => 'getPageRecord',
        'contentPid' => 'getContentFromPid',
    ];

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
        return new RuleDefinition('Migrate TypoScriptFrontendController readonly properties', [new CodeSample(
            <<<'CODE_SAMPLE'
$id = $GLOBALS['TSFE']->id;
$rootLine = $GLOBALS['TSFE']->rootLine;
$page = $GLOBALS['TSFE']->page;
$contentPid = $GLOBALS['TSFE']->contentPid;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$id = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getId();
$rootLine = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getRootLine();
$page = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord();
$contentPid = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getContentFromPid();
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
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

        $scope = ScopeFetcher::fetch($node);

        if (! isset(self::PROPERTY_TO_METHOD_MAP[$this->getName($node->name)])) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->createTYPO3RequestGetAttributeMethodCall($scope),
            self::PROPERTY_TO_METHOD_MAP[$this->getName($node->name)]
        );
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        if (! $this->isGlobals($propertyFetch)
            && ! $this->isObjectType(
                $propertyFetch->var,
                new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
            )
        ) {
            return true;
        }

        $propertyName = $this->getName($propertyFetch->name);
        if ($propertyName === null) {
            return true;
        }

        return ! in_array($propertyName, ['id', 'rootLine', 'page', 'contentPid'], true);
    }

    private function isGlobals(PropertyFetch $propertyFetch): bool
    {
        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    private function createTYPO3RequestGetAttributeMethodCall(Scope $scope): MethodCall
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $classReflection->is('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            $requestFetcherVariable = $this->nodeFactory->createPropertyFetch('this', 'request');
        } else {
            $requestFetcherVariable = $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
        }

        return $this->nodeFactory->createMethodCall(
            $requestFetcherVariable,
            'getAttribute',
            [new Arg(new String_('frontend.page.information'))]
        );
    }
}
