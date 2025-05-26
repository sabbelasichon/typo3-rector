<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.3/Deprecation-104223-FluidStandaloneMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateFluidStandaloneMethodsRector\MigrateFluidStandaloneMethodsRectorTest
 */
final class MigrateFluidStandaloneMethodsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string[]
     */
    private const UNIVERSAL_TAG_ARGUMENTS = [
        'class',
        'dir',
        'id',
        'lang',
        'style',
        'title',
        'accesskey',
        'tabindex',
        'onclick',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate Fluid standalone methods', [new CodeSample(
            <<<'CODE_SAMPLE'
public function initializeArguments(): void
{
    parent::initializeArguments();
    $this->registerUniversalTagAttributes();
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
public function initializeArguments(): void
{
    parent::initializeArguments();
}
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
if (empty($this->arguments['title']) && $title) {
    $this->tag->addAttribute('title', $title);
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
if (empty($this->additionalArguments['title']) && $title) {
    $this->tag->addAttribute('title', $title);
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class, ArrayDimFetch::class];
    }

    /**
     * @param Expression|ArrayDimFetch $node
     */
    public function refactor(Node $node)
    {
        if ($node instanceof Expression && $node->expr instanceof MethodCall) {
            if ($this->shouldSkipMethodCall($node->expr)) {
                return null;
            }

            return NodeVisitor::REMOVE_NODE;
        }

        if ($node instanceof ArrayDimFetch) {
            return $this->refactorArrayDimFetch($node);
        }

        return null;
    }

    private function shouldSkipMethodCall(MethodCall $methodCall): bool
    {
        if (! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper')
        )) {
            return true;
        }

        return ! $this->isName($methodCall->name, 'registerUniversalTagAttributes');
    }

    private function refactorArrayDimFetch(ArrayDimFetch $arrayDimFetch): ?Node
    {
        // Check if var is $this->arguments
        if (! $arrayDimFetch->var instanceof PropertyFetch) {
            return null;
        }

        $propertyFetch = $arrayDimFetch->var;

        if (! $this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper')
        )) {
            return null;
        }

        if (! $this->isName($propertyFetch->name, 'arguments')) {
            return null;
        }

        if (! $arrayDimFetch->dim instanceof String_) {
            return null;
        }

        $key = $arrayDimFetch->dim->value;

        if (! in_array($key, self::UNIVERSAL_TAG_ARGUMENTS, true)) {
            return null;
        }

        $propertyFetch->name = new Identifier('additionalArguments');

        return $arrayDimFetch;
    }
}
