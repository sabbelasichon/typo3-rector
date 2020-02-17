<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89579-ServiceChainsRequireAnArrayForExcludedServiceKeys.html
 */
final class ExcludeServiceKeysToArrayRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param Node|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isExpectedObjectType($node)) {
            return null;
        }

        if (!$this->isNames($node->name, ['findService', 'makeInstanceService'])) {
            return null;
        }

        $arguments = $node->args;

        if (count($arguments) < 3) {
            return null;
        }

        $excludeServiceKeys = $arguments[2];

        if ($this->isArrayType($excludeServiceKeys->value)) {
            return null;
        }

        $node->args[2] = $this->createStaticCall(GeneralUtility::class, 'trimExplode', [new String_(','), $excludeServiceKeys, $this->createTrue()]);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Change parameter $excludeServiceKeys explicity to an array', [
            new CodeSample(
                <<<'PHP'
GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', 'key1, key2');
ExtensionManagementUtility::findService('serviceType', 'serviceSubType', 'key1, key2');
PHP
                ,
                <<<'PHP'
GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);
PHP
            ),
        ]);
    }

    private function isExpectedObjectType(StaticCall $node): bool
    {
        return $this->isMethodStaticCallOrClassMethodObjectType($node, ExtensionManagementUtility::class)
               || $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class);
    }
}
