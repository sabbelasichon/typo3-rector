<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\ClassReflection;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102968-FormEngineItemFormElIDRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\SubstituteItemFormElIDRector\SubstituteItemFormElIDRectorTest
 */
final class SubstituteItemFormElIDRector extends AbstractRector
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
        return new RuleDefinition('Substitute itemFormElID key with custom generator', [new CodeSample(
            <<<'CODE_SAMPLE'
$attributeId = htmlspecialchars($this->data['parameterArray']['itemFormElID']);
$html[] = '<input id="' . $attributeId . '">';
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$attributeId = htmlspecialchars(StringUtility::getUniqueId(self::class . '-'));
$html[] = '<input id="' . $attributeId . '">';
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [ArrayDimFetch::class];
    }

    /**
     * @param ArrayDimFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->dim === null) {
            return null;
        }

        $key = $this->valueResolver->getValue($node->dim);

        if ($key !== 'itemFormElID') {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $classReflection = $scope->getClassReflection();

        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if (! $classReflection->isSubclassOf('TYPO3\CMS\Backend\Form\NodeInterface')) {
            return null;
        }

        return $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\StringUtility', 'getUniqueId', [
            new Concat($this->nodeFactory->createClassConstReference('self'), new String_('-')),
        ]);
    }
}
