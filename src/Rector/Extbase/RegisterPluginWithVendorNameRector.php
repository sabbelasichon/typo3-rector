<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.1/Deprecation-88995-CallingRegisterPluginWithVendorName.html
 */
final class RegisterPluginWithVendorNameRector extends AbstractRector
{
    /**
     * @inheritDoc
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
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, ExtensionUtility::class)) {
            return null;
        }

        if (!$this->isName($node->name, 'registerPlugin')) {
            return null;
        }

        return $this->removeVendorNameIfNeeded($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Remove vendor name from registerPlugin call',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
   'TYPO3.CMS.Form',
   'Formframework',
   'Form',
   'content-form',
);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
   'Form',
   'Formframework',
   'Form',
   'content-form',
);
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param StaticCall $node
     */
    private function removeVendorNameIfNeeded(Node $node): ?Node
    {
        $arguments = $node->args;
        $firstArgument = array_shift($arguments);
        $extensionName = $this->getValue($firstArgument->value);

        $delimiterPosition = strrpos($extensionName, '.');

        if (false === $delimiterPosition) {
            return null;
        }

        $extensionName = substr($extensionName, $delimiterPosition + 1);
        $node->args[0] = $this->createArg($extensionName);

        return $node;
    }
}
