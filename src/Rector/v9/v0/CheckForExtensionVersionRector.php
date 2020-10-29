<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82896-SystemExtensionVersionMigratedIntoWorkspaces.html
 */
final class CheckForExtensionVersionRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isExtensionManagementUtilityIsLoaded($node) && ! $this->isPackageManagerIsActivePackage($node)) {
            return null;
        }
        $firstArgument = $node->args[0];
        if (! $this->isValue($firstArgument->value, 'version')) {
            return null;
        }
        $firstArgument->value = new String_('workspaces');
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Change the extensions to check for workspaces instead of version.', [
            new CodeSample(<<<'PHP'
if (ExtensionManagementUtility::isLoaded('version')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isActive('version')) {
}
PHP
, <<<'PHP'
if (ExtensionManagementUtility::isLoaded('workspaces')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isActive('workspaces')) {
}
PHP
),
        ]);
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    private function isExtensionManagementUtilityIsLoaded(Node $node): bool
    {
        return $node instanceof StaticCall && $this->isMethodStaticCallOrClassMethodObjectType(
            $node,
            ExtensionManagementUtility::class
        ) && $this->isName($node->name, 'isLoaded');
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    private function isPackageManagerIsActivePackage(Node $node): bool
    {
        return $this->isMethodStaticCallOrClassMethodObjectType($node, PackageManager::class) && $this->isName(
            $node->name,
            'isPackageActive'
        );
    }
}
