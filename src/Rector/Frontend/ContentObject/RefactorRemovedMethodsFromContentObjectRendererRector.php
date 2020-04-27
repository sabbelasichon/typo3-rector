<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\ContentObject;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-72361-RemovedDeprecatedContentObjectWrappers.html
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if (! in_array($methodName, self::METHODS_TO_REFACTOR, true)) {
            return null;
        }

        $args = [
            $this->createArg($methodName),
            array_shift($node->args),
        ];

        return $this->createMethodCall($node->var, 'cObjGetSingle', $args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor removed methods from ContentObjectRenderer.', [
            new CodeSample(
                <<<'PHP'
$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
PHP
                ,
                <<<'PHP'
$cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
PHP
            ),
        ]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        $staticType = $this->getStaticType($methodCall->var);
        if ($staticType instanceof TypeWithClassName) {
            if (ContentObjectRenderer::class === $staticType->getClassName()) {
                return false;
            }
        }

        if ($this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals($methodCall, Typo3NodeResolver::TypoScriptFrontendController, 'cObj')) {
            return false;
        }

        return true;
    }
}
