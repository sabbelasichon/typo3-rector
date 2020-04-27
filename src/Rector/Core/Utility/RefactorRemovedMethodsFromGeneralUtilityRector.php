<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-72342-RemovedDeprecatedCodeFromGeneralUtility.html
 */
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
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->class, GeneralUtility::class)) {
            return null;
        }

        $methodName = $this->getName($node->name);
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

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor removed methods from GeneralUtility.', [
            new CodeSample(
                'GeneralUtility::gif_compress();',
                '\TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress();'
            ),
        ]);
    }
}
