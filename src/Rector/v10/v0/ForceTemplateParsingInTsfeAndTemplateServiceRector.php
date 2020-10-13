<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-88792-ForceTemplateParsingInTSFEAndTemplateService.html
 */
final class ForceTemplateParsingInTsfeAndTemplateServiceRector extends AbstractRector
{

    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    public function getNodeTypes(): array
    {
        return [PropertyFetch::class, Assign::class];
    }

    /**
     * @param PropertyFetch|Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->isObjectType($node->var, TypoScriptFrontendController::class)
            || $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
                $node,
                Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
            )) {


            if ($this->isName($node->name, 'forceTemplateParsing')) {


                $staticCallContext = $this->createStaticCall(GeneralUtility::class, 'makeInstance', [
                    $this->createClassConstantReference(Context::class),
                ]);
                if ($node instanceof Assign) {
                    $staticCallAspect = $this->createStaticCall(GeneralUtility::class, 'makeInstance', [
                        $this->createClassConstantReference(TypoScriptAspect::class),
                        new ConstFetch(new Name('true'))
                    ]);

                    $contextCall = $this->createMethodCall($staticCallContext, 'setAspect');

                    $contextCall->args = $this->createArgs(['typoscript', $staticCallAspect]);

                    $this->addNodeAfterNode($contextCall, $node);

                    try {
                        $this->removeNode($node);
                    } catch (ShouldNotHappenException $shouldNotHappenException) {
                        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
                        $this->removeNode($parentNode);
                    }
                } elseif($node instanceof PropertyFetch){
                    $contextCall = $this->createMethodCall($staticCallContext, 'getPropertyFromAspect');
                    $contextCall->args = $this->createArgs(['typoscript', 'forcedTemplateParsing']);
                }
                return $contextCall;
            }
        }


        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Force template parsing in tsfe is replaced with context api and aspects',
            [
                new CodeSample(
                    <<<'PHP'
$forceTemplateParsing = $GLOBALS['TSFE']->forceTemplateParsing;
$GLOBALS['TSFE']->forceTemplateParsing = true;
PHP
                    ,
                    <<<'PHP'
$forceTemplateParsing = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->setAspect('typoscript', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\TypoScriptAspect::class, true));
PHP
                ),
            ]
        );
    }
}
