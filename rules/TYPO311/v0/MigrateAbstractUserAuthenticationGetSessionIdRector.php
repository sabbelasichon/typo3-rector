<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-93023-ReworkedSessionHandling.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationGetSessionIdRector\MigrateAbstractUserAuthenticationGetSessionIdRectorTest
 */
final class MigrateAbstractUserAuthenticationGetSessionIdRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate `FrontendUserAuthentication->getSessionId()` and `BackendUserAuthentication->getSessionId()`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$frontendUserAuthentication = new FrontendUserAuthentication();
$id = $frontendUserAuthentication->getSessionId();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$frontendUserAuthentication = new FrontendUserAuthentication();
$id = $frontendUserAuthentication->getSession()->getIdentifier();
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$backendUserAuthentication = new BackendUserAuthentication();
$id = $backendUserAuthentication->getSessionId();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$backendUserAuthentication = new BackendUserAuthentication();
$id = $backendUserAuthentication->getSession()->getIdentifier();
CODE_SAMPLE
                ),

            ]
        );
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($node->var, 'getSession'),
            'getIdentifier'
        );
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Core\Authentication\AbstractUserAuthentication')
        )) {
            return true;
        }

        return ! $this->isName($methodCall->name, 'getSessionId');
    }
}
