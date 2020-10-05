<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * @see RefactorRemovedMethodsFromExtensionUtilityRectorTest
 */
final class RefactorRemovedMethodsFromExtensionUtilityRector extends AbstractRector
{


    /**
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
        $className = $this->getName($node->class);
        $methodName = $this->getName($node->name);

        if (ExtensionUtility::class !== $className) {
            return null;
        }

        if ('configureModule' !== $methodName) {
            return null;
        }

        $arguments = $node->args;
        return $this->createStaticCall(
            ExtensionManagementUtility::class,
            'configureModule', $arguments
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configureModule() gets replaced with \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::configureModule()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configureModule(
    'moduleSignature',
    'modulePath'
);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::configureModule(
    'moduleSignature',
    'modulePath'
);
CODE_SAMPLE
                )
            ]);
    }
}
