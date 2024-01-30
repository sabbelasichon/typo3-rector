<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97752-MailerAdapterInterfaceRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveMailerAdapterInterfaceRector\RemoveMailerAdapterInterfaceRectorTest
 */
final class RemoveMailerAdapterInterfaceRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof Class_) {
            return null;
        }

        if ($this->shouldSkip($node)) {
            return null;
        }

        $implements = [];
        foreach ($node->implements as $implement) {
            if (! $this->isName($implement, 'TYPO3\CMS\Mail\MailerAdapterInterface')) {
                $implements[] = $implement;
            }
        }

        $node->implements = $implements;

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor AdditionalFieldProvider classes', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class RemoveMailerAdapterInterfaceFixture implements TYPO3\CMS\Mail\MailerAdapterInterface
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class RemoveMailerAdapterInterfaceFixture
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(Class_ $class): bool
    {
        foreach ($class->implements as $implement) {
            if ($this->isName($implement, 'TYPO3\CMS\Mail\MailerAdapterInterface')) {
                return false;
            }
        }

        return true;
    }
}
