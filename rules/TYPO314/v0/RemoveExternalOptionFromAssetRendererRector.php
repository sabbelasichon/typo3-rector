<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107927-ExternalAttributesRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveExternalOptionFromAssetRendererRector\RemoveExternalOptionFromAssetRendererRectorTest
 */
final class RemoveExternalOptionFromAssetRendererRector extends AbstractRector implements DocumentedRuleInterface
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
        return new RuleDefinition('Remove "external" option from AssetRenderer', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Page\AssetCollector;

class MyClass
{
    public function __construct(private readonly AssetCollector $assetCollector)
    {
    }

    public function render()
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            '/styles/main.css',
            [],
            ['external' => true]
        );
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Page\AssetCollector;

class MyClass
{
    public function __construct(private readonly AssetCollector $assetCollector)
    {
    }

    public function render()
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            'URI:/styles/main.css'
        );
    }
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isNames($node->name, ['addStyleSheet', 'addJavaScript'])) {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\Page\AssetCollector'))) {
            return null;
        }

        $args = $node->getArgs();
        $hasChanged = false;

        // Check and update the source argument (second argument)
        if (isset($args[1])) {
            $sourceValue = $this->valueResolver->getValue($args[1]->value);

            if (is_string($sourceValue) && str_starts_with($sourceValue, '/')) {
                $args[1]->value = new String_('URI:' . $sourceValue);
                $hasChanged = true;
            }
        }

        // Check and update the options argument (fourth argument)
        if (isset($args[3]) && $args[3]->value instanceof Array_) {
            $optionsArray = $args[3]->value;

            foreach ($optionsArray->items as $index => $item) {
                if ($item === null) {
                    continue;
                }

                if ($this->valueResolver->isValue($item->key, 'external')) {
                    unset($optionsArray->items[$index]);
                    $hasChanged = true;
                }
            }
        }

        // Cleanup empty arguments
        if ($this->shouldRemoveArgument($args, 3)) {
            unset($node->args[3]);
            $hasChanged = true;

            // Re-fetch args to check if we can remove the 3rd argument (attributes)
            // Only remove 3rd if 4th is also gone/empty
            if ($this->shouldRemoveArgument($node->args, 2)) {
                unset($node->args[2]);
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    /**
     * @param array<int, Node\Arg> $args
     */
    private function shouldRemoveArgument(array $args, int $index): bool
    {
        if (! isset($args[$index])) {
            return false;
        }

        return $this->valueResolver->isValue($args[$index]->value, []);
    }
}
