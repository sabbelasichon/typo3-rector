<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106869-RemoveStaticFunctionParameterInAuthenticationService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveParameterInAuthenticationServiceRector\RemoveParameterInAuthenticationServiceRectorTest
 */
final class RemoveParameterInAuthenticationServiceRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove second argument `$passwordTransmissionStrategy` from `AuthenticationService->processLoginData()`',
            [
            new CodeSample(
                <<<'CODE_SAMPLE'
AuthenticationService->processLoginData($processedLoginData, 'normal');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
AuthenticationService->processLoginData($processedLoginData);
CODE_SAMPLE
            ),
        ]);
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
        if (! $this->isName($node->name, 'processLoginData')) {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\Authentication\AuthenticationService'))) {
            return null;
        }

        if (count($node->args) !== 2) {
            return null;
        }

        unset($node->args[1]);

        return $node;
    }
}
