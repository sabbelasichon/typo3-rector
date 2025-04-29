<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106056-AddSetRequestAndGetRequestToExtbaseValidatorInterface.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector\ExtendExtbaseValidatorsFromAbstractValidatorRectorTest
 */
final class ExtendExtbaseValidatorsFromAbstractValidatorRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Extend Extbase Validators from AbstractValidator', [new CodeSample(
            <<<'CODE_SAMPLE'
class MyValidator implements ValidatorInterface
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class MyValidator extends AbstractValidator
{
}
CODE_SAMPLE
        )]);
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $node->implements = array_filter(
            $node->implements,
            fn (FullyQualified $interface) => ! $this->isName(
                $interface,
                'TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface'
            )
        );
        $node->extends = new FullyQualified('TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator');

        return $node;
    }

    private function shouldSkip(Class_ $class): bool
    {
        $implementsInterface = false;
        foreach ($class->implements as $interface) {
            if ($this->isName($interface, 'TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface')) {
                $implementsInterface = true;
            }
        }

        if (! $implementsInterface) {
            return true;
        }

        return $class->extends instanceof Name && ! $this->isName(
            $class->extends,
            'TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator'
        );
    }
}
