<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class CheckForExtensionInfoRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isExtensionManagementUtilityIsLoaded($node) && !$this->isPackageManagerIsActivePackage($node)) {
            return null;
        }

        $arguments = $node->args;
        $firstArgument = array_shift($arguments);
        $firstArgumentValue = $this->getValue($firstArgument->value);

        if ('info_pagetsconfig' !== $firstArgumentValue) {
            return null;
        }

        $firstArgument->value = new Node\Scalar\String_('info');

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Change the extensions to check for info instead of info_pagetsconfig.', [
            new CodeSample(
                <<<'PHP'
if(ExtensionManagementUtility::isLoaded('info_pagetsconfig')) {

}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if($packageManager->isActive('info_pagetsconfig')) {

}
PHP
                ,
                <<<'PHP'

}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if($packageManager->isActive('info')) {

}
PHP
            ),
        ]);
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    private function isExtensionManagementUtilityIsLoaded(Node $node): bool
    {
        return $node instanceof StaticCall && $this->isMethodStaticCallOrClassMethodObjectType($node, ExtensionManagementUtility::class) && $this->isName($node, 'isLoaded');
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    private function isPackageManagerIsActivePackage(Node $node): bool
    {
        return $this->isObjectType($node, PackageManager::class) && $this->isName($node, 'isPackageActive');
    }
}
