<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80527-Marker-relatedMethodsInContentObjectRenderer.html
 */
final class RefactorRemovedMarkerMethodsFromContentObjectRendererRector extends AbstractRector
{
    /**
     * @var string
     */
    private const FILL_IN_MARKER_ARRAY = 'fillInMarkerArray';

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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(ContentObjectRenderer::class)
        )) {
            return null;
        }
        if (! $this->isNames(
            $node->name,
            [
                'getSubpart',
                'substituteSubpart',
                'substituteSubpartArray',
                'substituteMarker',
                'substituteMarkerArrayCached',
                'substituteMarkerArray',
                'substituteMarkerInObject',
                'substituteMarkerAndSubpartArrayRecursive',
                self::FILL_IN_MARKER_ARRAY,
            ]
        )) {
            return null;
        }
        if ($this->isNames(
            $node->name,
            [
                'getSubpart',
                'substituteSubpart',
                'substituteSubpartArray',
                'substituteMarker',
                'substituteMarkerArrayCached',
                'substituteMarkerArray',
                'substituteMarkerInObject',
                'substituteMarkerAndSubpartArrayRecursive',
            ]
        )) {
            $methodName = $this->getName($node->name);
            if (null === $methodName) {
                return null;
            }
            $classConstant = $this->nodeFactory->createClassConstReference(MarkerBasedTemplateService::class);
            $staticCall = $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [$classConstant]);
            return $this->nodeFactory->createMethodCall($staticCall, $methodName, $node->args);
        }
        if ($this->isName($node->name, self::FILL_IN_MARKER_ARRAY)) {
            $node->args[] = $this->nodeFactory->createArg(
                new BooleanNot($this->nodeFactory->createFuncCall(
                    'empty',
                    [$this->nodeFactory->createArg(
                        $this->nodeFactory->createPropertyFetch(new ArrayDimFetch(new Variable('GLOBALS'), new String_(
                'TSFE'
            )), 'xhtmlDoctype')
                    )]
                ))
            );
            return $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createStaticCall(
                    GeneralUtility::class,
                    'makeInstance',
                    [$this->nodeFactory->createClassConstReference(MarkerBasedTemplateService::class)]
                ),
                self::FILL_IN_MARKER_ARRAY,
                $node->args
            );
        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor removed Marker-related methods from ContentObjectRenderer.', [
            new CodeSample(<<<'CODE_SAMPLE'
// build template
$template = $this->cObj->getSubpart($this->config['templateFile'], '###TEMPLATE###');
$html = $this->cObj->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
$html2 = $this->cObj->substituteSubpartArray($html2, []);
$content .= $this->cObj->substituteMarker($content, $marker, $markContent);
$content .= $this->cObj->substituteMarkerArrayCached($template, $markerArray, $subpartArray, []);
$content .= $this->cObj->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
$content .= $this->cObj->substituteMarkerInObject($tree, $markContentArray);
$content .= $this->cObj->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
$content .= $this->cObj->fillInMarkerArray($markContentArray, $row, $fieldList, $nl2br, $prefix, $HSC);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
// build template
use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$template = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->getSubpart($this->config['templateFile'], '###TEMPLATE###');
$html = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
$html2 = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteSubpartArray($html2, []);
$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarker($content, $marker, $markContent);
$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerArrayCached($template, $markerArray, $subpartArray, []);
$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerInObject($tree, $markContentArray);
$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->fillInMarkerArray($markContentArray, $row, $fieldList, $nl2br, $prefix, $HSC, !empty($GLOBALS['TSFE']->xhtmlDoctype));
CODE_SAMPLE
),
        ]);
    }
}
