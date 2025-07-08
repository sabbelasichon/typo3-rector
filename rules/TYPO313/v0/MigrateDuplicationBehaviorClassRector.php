<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-101151-DuplicationBehaviorClass.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateDuplicationBehaviorClassRector\MigrateDuplicationBehaviorClassRectorTest
 */
final class MigrateDuplicationBehaviorClassRector extends AbstractRector implements DocumentedRuleInterface, MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert usages of DuplicationBehavior to its Enum equivalent', [new CodeSample(
            <<<'CODE_SAMPLE'
$file->copyTo($folder, null, \TYPO3\CMS\Core\Resource\DuplicationBehavior::REPLACE);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$file->copyTo($folder, null, \TYPO3\CMS\Core\Resource\Enum\DuplicationBehavior::REPLACE);
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

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! isset($node->args[2])) {
            return null;
        }

        $thirdArg = $node->args[2]->value;
        if (! $thirdArg instanceof ClassConstFetch) {
            return null;
        }

        if (! $this->isName($thirdArg->class, 'TYPO3\CMS\Core\Resource\DuplicationBehavior')) {
            return null;
        }

        $thirdArg->class = new FullyQualified('TYPO3\CMS\Core\Resource\Enum\DuplicationBehavior');

        return $node;
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->isName($node->name, 'copyTo')) {
            return true;
        }

        return ! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\Resource\AbstractFile'));
    }
}
