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

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82505-MergedEXTinfo_pagetsconfigToEXTinfo.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\CheckForExtensionInfoRector\CheckForExtensionInfoRectorTest
 */
final class CheckForExtensionInfoRector extends AbstractRector
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
        if (! $this->valueResolver->isValue($firstArgument->value, 'info_pagetsconfig')) {
            return null;
        }

        $firstArgument->value = new String_('info');
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change the extensions to check for info instead of info_pagetsconfig.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
if (ExtensionManagementUtility::isLoaded('info_pagetsconfig')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isActive('info_pagetsconfig')) {
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'

if (ExtensionManagementUtility::isLoaded('info')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isActive('info')) {
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    private function isExtensionManagementUtilityIsLoaded($call): bool
    {
        return $call instanceof StaticCall && $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $call,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        ) && $this->isName($call->name, 'isLoaded');
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    private function isPackageManagerIsActivePackage($call): bool
    {
        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $call,
            new ObjectType('TYPO3\CMS\Core\Package\PackageManager')
        ) && $this->isName($call->name, 'isPackageActive');
    }
}
