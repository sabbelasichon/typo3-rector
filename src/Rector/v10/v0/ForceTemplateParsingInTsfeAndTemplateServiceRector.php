<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-88792-ForceTemplateParsingInTSFEAndTemplateService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector\ForceTemplateParsingInTsfeAndTemplateServiceRectorTest
 */
final class ForceTemplateParsingInTsfeAndTemplateServiceRector extends AbstractRector
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
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

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
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($this->isPropertyForceTemplateParsing($node->var)) {
            //$node->var (left side is the target property, so its an assigment to it)

            $contextCall = $this->createCallForSettingProperty();
            $this->addNodeAfterNode($contextCall, $node);

            try {
                $this->removeNode($node);
            } catch (ShouldNotHappenException $shouldNotHappenException) {
                $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
                $this->removeNode($parentNode);
            }

            return $node;
        }

        $contextCall = $this->createCallForFetchingProperty();
        $node->expr = $contextCall;

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Force template parsing in tsfe is replaced with context api and aspects',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$myvariable = $GLOBALS['TSFE']->forceTemplateParsing;
$myvariable2 = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;

$GLOBALS['TSFE']->forceTemplateParsing = true;
$GLOBALS['TSFE']->tmpl->forceTemplateParsing = true;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$myvariable = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
$myvariable2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

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

        $staticCallAspect = $this->nodeFactory->createStaticCall(GeneralUtility::class, self::MAKE_INSTANCE, [
            $this->nodeFactory->createClassConstReference(TypoScriptAspect::class),
            new ConstFetch(new Name('true')),
        ]);

        $contextCall = $this->nodeFactory->createMethodCall($staticCallContext, 'setAspect');
        $contextCall->args = $this->nodeFactory->createArgs([self::TYPOSCRIPT, $staticCallAspect]);

        return $contextCall;
    }

    private function createContext(): StaticCall
    {
        return $this->nodeFactory->createStaticCall(GeneralUtility::class, self::MAKE_INSTANCE, [
            $this->nodeFactory->createClassConstReference(Context::class),
        ]);
    }

    private function isPropertyForceTemplateParsing(Node $node): bool
    {
        if (! property_exists($node, 'name')) {
            return false;
        }

        $nodeName = $node instanceof MethodCall ? $node->name : $node;

        if (! $this->isName($nodeName, 'forceTemplateParsing')) {
            return false;
        }

        if ($this->isObjectType($node, new ObjectType(TypoScriptFrontendController::class))) {
            return true;
        }

        if ($this->isObjectType($node, new ObjectType(TemplateService::class))) {
            return true;
        }

        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return true;
        }

        if (! property_exists($node, 'var')) {
            return false;
        }

        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node->var,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    private function shouldSkip(Assign $node): bool
    {
        if ($this->isPropertyForceTemplateParsing($node->var)) {
            return false;
        }
        return ! $this->isPropertyForceTemplateParsing($node->expr);
    }
}
