<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.0/Breaking-72384-RemovedDeprecatedCodeFromHtmlParser.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v0\CoreRector\Html\RefactorRemovedMarkerMethodsFromHtmlParserRectorTest
 */
final class RefactorRemovedMarkerMethodsFromHtmlParserRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private const MOVED_METHODS_TO_MARKER_BASED_TEMPLATES = [
        'getSubpart',
        'substituteSubpart',
        'substituteSubpartArray',
        'substituteMarker',
        'substituteMarkerArray',
        'substituteMarkerAndSubpartArrayRecursive',
    ];

    /**
     * @var string
     */
    private const RENAMED_METHOD = 'XHTML_clean';

    /**
     * @var string[]
     */
    private const REMOVED_METHODS = ['processTag', 'processContent'];

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     * @return int|null|Node
     */
    public function refactor(Node $node)
    {
        $staticOrMethodCall = $node->expr;

        if (! $staticOrMethodCall instanceof StaticCall && ! $staticOrMethodCall instanceof MethodCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticOrMethodCall,
            new ObjectType('TYPO3\CMS\Core\Html\HtmlParser')
        )) {
            return null;
        }

        if ($this->shouldSkip($staticOrMethodCall)) {
            return null;
        }

        $migratedNode = $this->migrateMethodsToMarkerBasedTemplateService($staticOrMethodCall);
        if ($migratedNode instanceof Node) {
            return $migratedNode;
        }

        $this->renameMethod($staticOrMethodCall);

        return $this->removeMethods($staticOrMethodCall);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor removed Marker-related methods from HtmlParser.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Html\HtmlParser;

final class HtmlParserMarkerRendererMethods
{
    public function doSomething(): void
    {
        $template = '';
        $markerArray = [];
        $subpartArray = [];
        $htmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(HtmlParser::class);
        $template = $htmlparser->getSubpart($this->config['templateFile'], '###TEMPLATE###');
        $html = $htmlparser->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
        $html2 = $htmlparser->substituteSubpartArray($html2, []);

        $html3 = $htmlparser->processTag($value, $conf, $endTag, $protected = 0);
        $html4 = $htmlparser->processContent($value, $dir, $conf);

        $content = $htmlparser->substituteMarker($content, $marker, $markContent);
        $content .= $htmlparser->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
        $content .= $htmlparser->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
        $content = $htmlparser->XHTML_clean($content);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Html\HtmlParser;

final class HtmlParserMarkerRendererMethods
{
    public function doSomething(): void
    {
        $template = '';
        $markerArray = [];
        $subpartArray = [];
        $htmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(HtmlParser::class);
        $template = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->getSubpart($this->config['templateFile'], '###TEMPLATE###');
        $html = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
        $html2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteSubpartArray($html2, []);

        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteMarker($content, $marker, $markContent);
        $content .= \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
        $content .= \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
        $content = $htmlparser->HTMLcleaner($content);
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    public function removeMethods($call): ?int
    {
        if ($this->isNames($call->name, self::REMOVED_METHODS)) {
            $methodName = $this->getName($call->name);
            if ($methodName !== null) {
                return NodeTraverser::REMOVE_NODE;
            }
        }
        return null;
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    public function renameMethod($call): void
    {
        if ($this->isName($call->name, self::RENAMED_METHOD)) {
            $methodName = $this->getName($call->name);
            if ($methodName !== null) {
                $call->name = new Identifier('HTMLcleaner');
            }
        }
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    private function migrateMethodsToMarkerBasedTemplateService($call): ?Node
    {
        if ($this->isNames($call->name, self::MOVED_METHODS_TO_MARKER_BASED_TEMPLATES)) {
            $methodName = $this->getName($call->name);
            if ($methodName !== null) {
                $classConstant = $this->nodeFactory->createClassConstReference(
                    'TYPO3\CMS\Core\Service\MarkerBasedTemplateService'
                );
                $staticCall = $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    'makeInstance',
                    [$classConstant]
                );

                return $this->nodeFactory->createMethodCall($staticCall, $methodName, $call->args);
            }
        }

        return null;
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    private function shouldSkip($call): bool
    {
        $skip = false;
        if (! $this->isNames($call->name, self::MOVED_METHODS_TO_MARKER_BASED_TEMPLATES)
            && ! $this->isNames($call->name, self::REMOVED_METHODS)
            && ! $this->isName($call->name, self::RENAMED_METHOD)
        ) {
            $skip = true;
        }

        return $skip;
    }
}
