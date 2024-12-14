<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Deprecation-88792-ForceTemplateParsingInTSFEAndTemplateService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector\ForceTemplateParsingInTsfeAndTemplateServiceRectorTest
 */
final class ForceTemplateParsingInTsfeAndTemplateServiceRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string
     */
    private const MAKE_INSTANCE = 'makeInstance';

    /**
     * @var string
     */
    private const TYPOSCRIPT = 'typoscript';

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
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node)
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($this->isPropertyForceTemplateParsing($node->var)) {
            return $this->createCallForSettingProperty();
        }

        $node->expr = $this->createCallForFetchingProperty();

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Force template parsing in tsfe is replaced with context api and aspects',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$myVariable = $GLOBALS['TSFE']->forceTemplateParsing;
$myVariable2 = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;

$GLOBALS['TSFE']->forceTemplateParsing = true;
$GLOBALS['TSFE']->tmpl->forceTemplateParsing = true;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$myVariable = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
$myVariable2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->setAspect('typoscript', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\TypoScriptAspect::class, true));
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->setAspect('typoscript', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\TypoScriptAspect::class, true));
CODE_SAMPLE
                ),
            ]
        );
    }

    public function createCallForFetchingProperty(): MethodCall
    {
        $staticCallContext = $this->createContext();

        $contextCall = $this->nodeFactory->createMethodCall($staticCallContext, 'getPropertyFromAspect');
        $contextCall->args = $this->nodeFactory->createArgs([self::TYPOSCRIPT, 'forcedTemplateParsing']);

        return $contextCall;
    }

    public function createCallForSettingProperty(): MethodCall
    {
        $staticCallContext = $this->createContext();

        $staticCallAspect = $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            self::MAKE_INSTANCE,
            [
                $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Context\TypoScriptAspect'),
                new ConstFetch(new Name('true')),
            ]
        );

        $contextCall = $this->nodeFactory->createMethodCall($staticCallContext, 'setAspect');
        $contextCall->args = $this->nodeFactory->createArgs([self::TYPOSCRIPT, $staticCallAspect]);

        return $contextCall;
    }

    private function createContext(): StaticCall
    {
        return $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', self::MAKE_INSTANCE, [
            $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Context\Context'),
        ]);
    }

    private function shouldSkip(Assign $node): bool
    {
        if ($this->isPropertyForceTemplateParsing($node->var)) {
            return false;
        }

        return ! $this->isPropertyForceTemplateParsing($node->expr);
    }

    /**
     * @param PropertyFetch|Variable|MethodCall|Expr $node
     */
    private function isPropertyForceTemplateParsing($node): bool
    {
        if (! property_exists($node, 'name')) {
            return false;
        }

        $nodeName = $node instanceof MethodCall ? $node->name : $node;

        if (! $this->isName($nodeName, 'forceTemplateParsing')) {
            return false;
        }

        /** @var PropertyFetch $node */
        if ($this->isGlobals($node)) {
            return true;
        }

        if (! property_exists($node, 'var')) {
            return false;
        }

        /** @var PropertyFetch|MethodCall $propertyFetchOrMethodCall */
        $propertyFetchOrMethodCall = $node->var;
        if ($this->isObjectType(
            $propertyFetchOrMethodCall,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        )) {
            return true;
        }

        if ($this->isObjectType(
            $propertyFetchOrMethodCall,
            new ObjectType('TYPO3\CMS\Core\TypoScript\TemplateService')
        )) {
            return true;
        }

        return $this->isGlobals($propertyFetchOrMethodCall);
    }

    /**
     * @param PropertyFetch|MethodCall $propertyFetchOrMethodCall
     */
    private function isGlobals($propertyFetchOrMethodCall): bool
    {
        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetchOrMethodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
