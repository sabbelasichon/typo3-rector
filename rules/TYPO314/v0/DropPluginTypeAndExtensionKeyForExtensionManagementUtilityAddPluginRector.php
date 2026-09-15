<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-105377-DeprecatedFunctionalityRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\DropPluginTypeAndExtensionKeyForExtensionManagementUtilityAddPluginRector\DropPluginTypeAndExtensionKeyForExtensionManagementUtilityAddPluginRectorTest
 */
final class DropPluginTypeAndExtensionKeyForExtensionManagementUtilityAddPluginRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string[]
     */
    private const PLUGIN_TYPES = ['CType', 'list_type'];

    /**
     * @var string[]
     */
    private const PLUGIN_TYPE_CONSTANTS = ['PLUGIN_TYPE_CONTENT_ELEMENT', 'PLUGIN_TYPE_PLUGIN'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Drop the plugin type and the extension key of `ExtensionManagementUtility::addPlugin()`, the second parameter is the FlexForm data structure since TYPO3 v14',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
ExtensionManagementUtility::addPlugin(['My Plugin', 'my_plugin', 'my-icon'], 'CType', 'my_extension');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
ExtensionManagementUtility::addPlugin(['My Plugin', 'my_plugin', 'my-icon']);
CODE_SAMPLE
                ),
            ]
        );
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

        $args = $node->getArgs();
        if (count($args) < 2) {
            return null;
        }

        foreach ($args as $arg) {
            // named arguments cannot be dropped by position
            if ($arg->name instanceof Node\Identifier) {
                return null;
            }
        }

        $secondArgument = $args[1]->value;

        if ($this->isPluginType($secondArgument)) {
            $node->args = [$args[0]];

            return $node;
        }

        if ($secondArgument instanceof String_) {
            if (count($args) === 2) {
                return null;
            }

            $node->args = [$args[0], $args[1]];

            return $node;
        }

        // An unresolvable second argument is only known to be the plugin type if a third
        // argument follows, because TYPO3 v14 accepts two arguments at most.
        if (count($args) < 3) {
            return null;
        }

        $node->args = [$args[0]];

        return $node;
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if ($staticCall->isFirstClassCallable()) {
            return true;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return true;
        }

        return ! $this->isName($staticCall->name, 'addPlugin');
    }

    private function isPluginType(Expr $expr): bool
    {
        if ($expr instanceof String_) {
            return in_array($expr->value, self::PLUGIN_TYPES, true);
        }

        if ($expr instanceof ClassConstFetch) {
            return $this->isName($expr->class, 'TYPO3\CMS\Extbase\Utility\ExtensionUtility')
                && $this->isNames($expr->name, self::PLUGIN_TYPE_CONSTANTS);
        }

        // the FlexForm data structure is a non nullable string, so null is always the plugin type
        return $expr instanceof ConstFetch && $this->isName($expr->name, 'null');
    }
}
