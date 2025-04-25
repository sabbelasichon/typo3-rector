<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\LNumber;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-93023-ReworkedSessionHandling.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\MigrateAbstractUserAuthenticationCreateSessionIdRector\MigrateAbstractUserAuthenticationCreateSessionIdRectorTest
 */
final class MigrateAbstractUserAuthenticationCreateSessionIdRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate `FrontendUserAuthentication->createSessionId()` and `BackendUserAuthentication->createSessionId()` to `Random->generateRandomHexString(32)`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$frontendUserAuthentication = new \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication();
$sessionId = $frontendUserAuthentication->createSessionId();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$sessionId = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Random::class)->generateRandomHexString(32);
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
$backendUserAuthentication = new \TYPO3\CMS\Core\Authentication\BackendUserAuthentication();
$sessionId = $backendUserAuthentication->createSessionId();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$sessionId = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Random::class)->generateRandomHexString(32);
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
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'makeInstance',
                [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Crypto\Random')]
            ),
            'generateRandomHexString',
            [new Arg(new LNumber(32))]
        );
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\Authentication\AbstractUserAuthentication')
        )) {
            return true;
        }

        return ! $this->isName($methodCall->name, 'createSessionId');
    }
}
