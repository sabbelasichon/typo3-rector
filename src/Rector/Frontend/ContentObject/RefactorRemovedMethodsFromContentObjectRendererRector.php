<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\ContentObject;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class RefactorRemovedMethodsFromContentObjectRendererRector extends AbstractRector
{
    /**
     * @var array
     */
    private $methodsToRefactor = [
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
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node|MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, ContentObjectRenderer::class) && !$this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals($node, Typo3NodeResolver::TypoScriptFrontendController, 'cObj')) {
            return null;
        }

        $methodName = $this->getName($node);

        if (!in_array($methodName, $this->methodsToRefactor, true)) {
            return null;
        }

        return $this->createMethodCall($node->var, 'cObjGetSingle', [
            $this->createArg($methodName),
            array_shift($node->args),
        ]);
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
}
