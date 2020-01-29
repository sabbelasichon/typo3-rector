<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

final class RegisterPluginWithVendorNameRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\StaticCall::class];
    }

    /**
     * @param $node Node|Node\Expr\MethodCall
     *
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, ExtensionUtility::class)) {
            return null;
        }

        if (!$this->isName($node, 'registerPlugin')) {
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
     * @return Node|null
     */
    private function removeVendorNameIfNeeded(Node $node)
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
