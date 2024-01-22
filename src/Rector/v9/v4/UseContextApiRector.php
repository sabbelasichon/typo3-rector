<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85389-VariousPublicPropertiesInFavorOfContextAPI.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseContextApiRector\UseContextApiRectorTest
 */
final class UseContextApiRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private const REFACTOR_PROPERTIES = [
        'loginUser',
        'gr_list',
        'beUserLogin',
        'showHiddenPage',
        'showHiddenRecords',
    ];

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

        if ($this->shouldSkip($propertyFetch)) {
            return null;
        }

        $propertyName = $this->getName($propertyFetch->name);

        $staticCall = $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
            $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Context\Context'),
        ]);

        $contextCall = $this->nodeFactory->createMethodCall($staticCall, 'getPropertyFromAspect');

        if ($propertyName === 'loginUser') {
            $contextCall->args = $this->nodeFactory->createArgs(['frontend.user', 'isLoggedIn']);

            $node->expr = $contextCall;

            return $node;
        }

        if ($propertyName === 'gr_list') {
            $contextCall->args = $this->nodeFactory->createArgs(['frontend.user', 'groupIds']);

            $node->expr = $this->nodeFactory->createFuncCall('implode', [new String_(','), $contextCall]);

            return $node;
        }

        if ($propertyName === 'beUserLogin') {
            $contextCall->args = $this->nodeFactory->createArgs(['backend.user', 'isLoggedIn']);

            $node->expr = $contextCall;

            return $node;
        }

        if ($propertyName === 'showHiddenPage') {
            $contextCall->args = $this->nodeFactory->createArgs(['visibility', 'includeHiddenPages']);

            $node->expr = $contextCall;

            return $node;
        }

        if ($propertyName === 'showHiddenRecords') {
            $contextCall->args = $this->nodeFactory->createArgs(['visibility', 'includeHiddenContent']);

            $node->expr = $contextCall;

            return $node;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Various public properties in favor of Context API',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$frontendUserIsLoggedIn = $GLOBALS['TSFE']->loginUser;
$groupList = $GLOBALS['TSFE']->gr_list;
$backendUserIsLoggedIn = $GLOBALS['TSFE']->beUserLogin;
$showHiddenPage = $GLOBALS['TSFE']->showHiddenPage;
$showHiddenRecords = $GLOBALS['TSFE']->showHiddenRecords;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$frontendUserIsLoggedIn = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('frontend.user', 'isLoggedIn');
$groupList = implode(',', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('frontend.user', 'groupIds'));
$backendUserIsLoggedIn = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('backend.user', 'isLoggedIn');
$showHiddenPage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('visibility', 'includeHiddenPages');
$showHiddenRecords = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('visibility', 'includeHiddenContent');
CODE_SAMPLE
                ),
            ]
        );
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        $parentNode = $this->betterNodeFinder->findParentType($propertyFetch, Assign::class);

        // Check if we have an assigment to the property, if so do not change it
        if ($parentNode instanceof Assign && $parentNode->var instanceof PropertyFetch) {
            return true;
        }

        if (! $this->isNames($propertyFetch->name, self::REFACTOR_PROPERTIES)) {
            return true;
        }

        if ($this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
