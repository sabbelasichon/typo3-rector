<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.0/Breaking-72342-RemovedDeprecatedCodeFromGeneralUtility.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v0\RefactorRemovedMethodsFromGeneralUtilityRector\RefactorRemovedMethodsFromGeneralUtilityRectorTest
 */
final class RefactorRemovedMethodsFromGeneralUtilityRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
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
        if (! $this->isName($node->class, 'TYPO3\CMS\Core\Utility\GeneralUtility')) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        if ($methodName === 'gif_compress') {
            return $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Imaging\GraphicalFunctions',
                'gifCompress',
                $node->args
            );
        }

        if ($methodName === 'png_to_gif_by_imagemagick') {
            return $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Imaging\GraphicalFunctions',
                'pngToGifByImagemagick',
                $node->args
            );
        }

        if ($methodName === 'read_png_gif') {
            return $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Imaging\GraphicalFunctions',
                'readPngGif',
                $node->args
            );
        }

        if ($methodName === 'array_merge') {
            [$arg1, $arg2] = $node->args;

            return new Plus($arg1->value, $arg2->value);
        }

        if ($methodName === 'cleanOutputBuffers') {
            return $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'flushOutputBuffers');
        }

        if (in_array($methodName, [
            'inArray',
            'removeArrayEntryByValue',
            'keepItemsInArray',
            'remapArrayKeys',
            'arrayDiffAssocRecursive',
            'naturalKeySortRecursive',
        ], true)) {
            return $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Utility\ArrayUtility',
                $methodName,
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
        return new RuleDefinition('Refactor removed methods from GeneralUtility.', [
            new CodeSample('GeneralUtility::gif_compress();', GraphicalFunctions::class . '::gifCompress();'),
        ]);
    }
}
