<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-109280-FormEngineTcaDescriptionFieldInformation.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFromDefaultFieldInformationRector\RemoveTcaDescriptionFromDefaultFieldInformationRectorTest
 */
final class RemoveTcaDescriptionFromDefaultFieldInformationRector extends AbstractRector implements DocumentedRuleInterface
{
    private const TCA_DESCRIPTION = 'tcaDescription';

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
        return new RuleDefinition('Remove tcaDescription from $defaultFieldInformation of FormEngine nodes', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class MyCustomElement extends AbstractFormElement
{
    protected $defaultFieldInformation = [
        'tcaDescription' => [
            'renderType' => 'tcaDescription',
        ],
        'myCustomInfo' => [
            'renderType' => 'myCustomInfo',
        ],
    ];
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class MyCustomElement extends AbstractFormElement
{
    protected $defaultFieldInformation = [
        'myCustomInfo' => [
            'renderType' => 'myCustomInfo',
        ],
    ];
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, new ObjectType('TYPO3\CMS\Backend\Form\AbstractNode'))) {
            return null;
        }

        foreach ($node->stmts as $index => $stmt) {
            if (! $stmt instanceof Property) {
                continue;
            }

            if (count($stmt->props) !== 1 || ! $this->isName($stmt, 'defaultFieldInformation')) {
                continue;
            }

            $defaultFieldInformation = $stmt->props[0]->default;
            if (! $defaultFieldInformation instanceof Array_) {
                return null;
            }

            if (! $this->removeTcaDescriptionItems($defaultFieldInformation)) {
                return null;
            }

            if ($defaultFieldInformation->items === []) {
                unset($node->stmts[$index]);
            }

            return $node;
        }

        return null;
    }

    private function removeTcaDescriptionItems(Array_ $array): bool
    {
        $hasRemovedItem = false;
        foreach ($array->items as $index => $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }

            if (! $this->isTcaDescriptionNode($arrayItem)) {
                continue;
            }

            unset($array->items[$index]);
            $hasRemovedItem = true;
        }

        return $hasRemovedItem;
    }

    private function isTcaDescriptionNode(ArrayItem $arrayItem): bool
    {
        if (! $arrayItem->value instanceof Array_) {
            return false;
        }

        $hasRenderType = false;
        foreach ($arrayItem->value->items as $nodeOption) {
            if (! $nodeOption instanceof ArrayItem || ! $nodeOption->key instanceof Expr) {
                continue;
            }

            if (! $this->valueResolver->isValue($nodeOption->key, 'renderType')) {
                continue;
            }

            if ($this->valueResolver->isValue($nodeOption->value, self::TCA_DESCRIPTION)) {
                return true;
            }

            $hasRenderType = true;
        }

        return ! $hasRenderType
            && $arrayItem->key instanceof Expr
            && $this->valueResolver->isValue($arrayItem->key, self::TCA_DESCRIPTION);
    }
}
