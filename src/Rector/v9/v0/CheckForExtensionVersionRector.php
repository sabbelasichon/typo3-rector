<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82896-SystemExtensionVersionMigratedIntoWorkspaces.html
 */
final class CheckForExtensionVersionRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
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
        if (! $this->valueResolver->isValue($firstArgument->value, 'version')) {
            return null;
        }
        $firstArgument->value = new String_('workspaces');
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change the extensions to check for workspaces instead of version.', [
            new CodeSample(<<<'CODE_SAMPLE'
if (ExtensionManagementUtility::isLoaded('version')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isActive('version')) {
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
if (ExtensionManagementUtility::isLoaded('workspaces')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isActive('workspaces')) {
}
CODE_SAMPLE
),
        ]);
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    private function isExtensionManagementUtilityIsLoaded(Node $node): bool
    {
        return $node instanceof StaticCall && $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(ExtensionManagementUtility::class)
        ) && $this->isName($node->name, 'isLoaded');
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    private function isPackageManagerIsActivePackage(Node $node): bool
    {
        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(PackageManager::class)
        ) && $this->isName($node->name, 'isPackageActive');
    }
}
