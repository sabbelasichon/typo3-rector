<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rules\Rector\Misc;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddCodeCoverageIgnoreToMethodRectorDefinitionRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, AbstractRector::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'getDefinition')) {
            return null;
        }

        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $node->getAttribute(AttributeKey::PHP_DOC_INFO);

        if ($phpDocInfo->hasByName('codeCoverageIgnore')) {
            return null;
        }

        $phpDocInfo->addBareTag('codeCoverageIgnore');

        return null;
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
