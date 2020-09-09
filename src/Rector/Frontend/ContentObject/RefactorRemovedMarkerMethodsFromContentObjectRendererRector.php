<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\ContentObject;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80527-Marker-relatedMethodsInContentObjectRenderer.html
 */
final class RefactorRemovedMarkerMethodsFromContentObjectRendererRector extends AbstractRector
{
    /**
     * @var string
     */
    private const FILL_IN_MARKER_ARRAY = 'fillInMarkerArray';

    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ContentObjectRenderer::class)) {
            return null;
        }

        if (! $this->isNames($node->name, [
            'getSubpart',
            'substituteSubpart',
            'substituteSubpartArray',
            'substituteMarker',
            'substituteMarkerArrayCached',
            'substituteMarkerArray',
            'substituteMarkerInObject',
            'substituteMarkerAndSubpartArrayRecursive',
            self::FILL_IN_MARKER_ARRAY,
        ])) {
            return null;
        }

        if ($this->isNames($node->name, [
            'getSubpart',
            'substituteSubpart',
            'substituteSubpartArray',
            'substituteMarker',
            'substituteMarkerArrayCached',
            'substituteMarkerArray',
            'substituteMarkerInObject',
            'substituteMarkerAndSubpartArrayRecursive',
        ])) {
            $methodName = $this->getName($node->name);
            if (null === $methodName) {
                return null;
            }

            $classConstant = $this->createClassConstantReference(MarkerBasedTemplateService::class);
            $staticCall = $this->createStaticCall(GeneralUtility::class, 'makeInstance', [$classConstant]);

            return $this->createMethodCall($staticCall, $methodName, $node->args);
        }

        if ($this->isName($node->name, self::FILL_IN_MARKER_ARRAY)) {
            $node->args[] = $this->createArg(
                new BooleanNot($this->createFuncCall(
                    'empty',
                    [$this->createArg(
                        $this->createPropertyFetch(new ArrayDimFetch(new Variable('GLOBALS'), new String_(
                            'TSFE'
                        )), 'xhtmlDoctype')
                    )]
                ))
            );

            return $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->createClassConstantReference(MarkerBasedTemplateService::class),
            ]), self::FILL_IN_MARKER_ARRAY, $node->args);
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor removed Marker-related methods from ContentObjectRenderer.', [
            new CodeSample(
                <<<'PHP'
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
PHP
                ,
                <<<'PHP'
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
PHP
            ),
        ]);
    }
}
