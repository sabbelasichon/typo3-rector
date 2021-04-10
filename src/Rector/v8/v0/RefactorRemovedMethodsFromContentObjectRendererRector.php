<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-72361-RemovedDeprecatedContentObjectWrappers.html
 */
final class RefactorRemovedMethodsFromContentObjectRendererRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private const METHODS_TO_REFACTOR = [
        'FLOWPLAYER',
        'TEXT',
        'CLEARGIF',
        'COBJ_ARRAY',
        'USER',
        'FILE',
        'FILES',
        'IMAGE',
        'IMG_RESOURCE',
        'IMGTEXT',
        'CONTENT',
        'RECORDS',
        'HMENU',
        'CTABLE',
        'OTABLE',
        'COLUMNS',
        'HRULER',
        'CASEFUNC',
        'LOAD_REGISTER',
        'FORM',
        'SEARCHRESULT',
        'TEMPLATE',
        'FLUIDTEMPLATE',
        'MULTIMEDIA',
        'MEDIA',
        'SWFOBJECT',
        'QTOBJECT',
    ];

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
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $methodName = $this->getName($node->name);
        if (! in_array($methodName, self::METHODS_TO_REFACTOR, true)) {
            return null;
        }
        $args = [$this->nodeFactory->createArg($methodName), array_shift($node->args)];
        return $this->nodeFactory->createMethodCall($node->var, 'cObjGetSingle', $args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor removed methods from ContentObjectRenderer.', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
$cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
$cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
CODE_SAMPLE
),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        $staticType = $this->getStaticType($node->var);
        if ($staticType instanceof TypeWithClassName && ContentObjectRenderer::class === $staticType->getClassName()) {
            return false;
        }

        return ! $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'cObj'
        );
    }
}
