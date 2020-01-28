<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\ContentObject;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
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

    private $globals = [
        'GLOBALS' => true,
    ];

    /**
     * List of nodes this class checks, classes that implements \PhpParser\Node
     * See beautiful map of all nodes https://github.com/rectorphp/rector/blob/master/docs/NodesOverview.md.
     *
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node|Node\Expr\MethodCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, ContentObjectRenderer::class)) {
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
cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
PHP
            ),
        ]);
    }
}
