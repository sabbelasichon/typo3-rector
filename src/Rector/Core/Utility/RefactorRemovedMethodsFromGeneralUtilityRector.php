<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class RefactorRemovedMethodsFromGeneralUtilityRector extends AbstractRector
{
    /**
     * List of nodes this class checks, classes that implements \PhpParser\Node
     * See beautiful map of all nodes https://github.com/rectorphp/rector/blob/master/docs/NodesOverview.md.
     *
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node|StaticCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        $classNode = $node->class;
        $className = $this->getName($classNode);
        $methodName = $this->getName($node);

        if (GeneralUtility::class !== $className) {
            return null;
        }

        switch ($methodName) {
            case 'gif_compress':
                return $this->createStaticCall(GraphicalFunctions::class, 'gifCompress', $node->args);
                break;
            case 'png_to_gif_by_imagemagick':
                return $this->createStaticCall(GraphicalFunctions::class, 'pngToGifByImagemagick', $node->args);
                break;
            case 'read_png_gif':
                return $this->createStaticCall(GraphicalFunctions::class, 'readPngGif', $node->args);
                break;
            case 'inArray':
            case 'removeArrayEntryByValue':
            case 'keepItemsInArray':
            case 'remapArrayKeys':
            case 'arrayDiffAssocRecursive':
            case 'naturalKeySortRecursive':
                return $this->createStaticCall(ArrayUtility::class, $methodName, $node->args);
                break;
            case 'array_merge':
                [$arg1, $arg2] = $node->args;

                return new Node\Expr\BinaryOp\Plus($arg1->value, $arg2->value);
                break;
            case 'getThisUrl':
                // TODO: This is not implemented yet. What is the correct equivalent within getIndpEnv
                break;
            case 'cleanOutputBuffers':
                return $this->createStaticCall(GeneralUtility::class, 'flushOutputBuffers');
                break;
        }

        return null;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor removed methods from GeneralUtility.', [
            new CodeSample(
                <<<'PHP'
GeneralUtility::gif_compress();
PHP
                ,
                <<<'PHP'
\TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress();
PHP
            ),
        ]);
    }
}
