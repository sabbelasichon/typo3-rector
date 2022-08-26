<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/12.0/Breaking-97787-AbstractMessageGetSeverityReturnsContextualFeedbackSeverity.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AbstractMessageGetSeverityRector\AbstractMessageGetSeverityRectorTest
 */
final class AbstractMessageGetSeverityRector extends AbstractRector
{

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
    public function refactor(Node $node)
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isNames($node->name, ['getSeverity'])) {
            return null;
        }

        return $this->nodeFactory->createPropertyFetch($node, 'value');

    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use value property on getSeverity()', [new CodeSample(
            <<<'CODE_SAMPLE'
use \TYPO3\CMS\Core\Messaging\FlashMessage;

$flashMessage = new FlashMessage('This is a message');
$severityAsInt = $flashMessage->getSeverity();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use \TYPO3\CMS\Core\Messaging\FlashMessage;

$flashMessage = new FlashMessage('This is a message');
$severityAsInt = $flashMessage->getSeverity()->value;
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\Messaging\FlashMessage')
        )) {
            return true;
        }

        return false;
    }
}
