<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rules\Rector\Misc;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ssch\TYPO3Rector\Rules\Tests\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRectorTest
 */
final class AddCodeCoverageIgnoreToMethodRectorDefinitionRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(AbstractRector::class)
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'getRuleDefinition')) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        if ($phpDocInfo->hasByName('codeCoverageIgnore')) {
            return null;
        }

        $phpDocInfo->addPhpDocTagNode(new PhpDocTagNode('@codeCoverageIgnore', new GenericTagValueNode('')));

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Adds @codeCoverageIgnore annotation to to method getDefinition', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    public function getDefinition(): RectorDefinition
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node): ?Node
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
    }
}
CODE_SAMPLE
            ),
        ]);
    }
}
