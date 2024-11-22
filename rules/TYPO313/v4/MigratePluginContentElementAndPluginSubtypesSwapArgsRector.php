<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105076-PluginContentElementAndPluginSubTypes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesSwapArgsRector\MigratePluginContentElementAndPluginSubtypesSwapArgsRectorTest
 */
final class MigratePluginContentElementAndPluginSubtypesSwapArgsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Swap arguments for ExtensionManagementUtility::addPiFlexFormValue', [new CodeSample(
            <<<'CODE_SAMPLE'
ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    $pluginSignature,
);
CODE_SAMPLE
        )]);
    }

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $argsCount = count($node->args);
        switch ($argsCount) {
            case 2:
                $this->swapArgs($node);
                return $node;
            case 3:
                if ($node->args[0]->value instanceof String_) {
                    $firstArgumentValue = $this->valueResolver->getValue($node->args[0]);

                    // check if already migrated
                    if ($firstArgumentValue === '*') {
                        return null;
                    }
                }

                if ($node->args[2]->value instanceof String_) {
                    $thirdArgumentValue = $this->valueResolver->getValue($node->args[2]);
                    if ($thirdArgumentValue === 'list' || $thirdArgumentValue === '*') {
                        $this->swapArgs($node);
                        return $node;
                    }
                }
        }

        return null;
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return true;
        }

        return ! $this->isName($staticCall->name, 'addPiFlexFormValue');
    }

    private function swapArgs(StaticCall $node): void
    {
        // put first argument on third place
        $firstArgument = $node->args[0];

        $node->args[0] = new Arg(new String_('*'));
        $node->args[2] = $firstArgument;
    }
}
