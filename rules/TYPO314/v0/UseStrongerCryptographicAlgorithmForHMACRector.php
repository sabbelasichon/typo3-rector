<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106307-UseStrongerCryptographicAlgorithmForHMAC.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrongerCryptographicAlgorithmForHMACRector\UseStrongerCryptographicAlgorithmForHMACRectorTest
 */
final class UseStrongerCryptographicAlgorithmForHMACRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use stronger cryptographic algorithm for HMAC', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Crypto\HashService;

$hash = $hashService->hmac($data, 'my-additional-secret');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Crypto\HashAlgo;
use TYPO3\CMS\Core\Crypto\HashService;

$hash = $hashService->hmac($data, 'my-additional-secret', HashAlgo::SHA3_256);
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

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'hmac')) {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\Crypto\HashService'))) {
            return null;
        }

        $args = $node->args;
        $argc = count($args);

        // If the 3rd argument (algorithm) is already present, we do not need to act
        if ($argc >= 3) {
            return null;
        }

        // If the 2nd argument (additionalSecret) is missing, generate a random 8-char string
        if ($argc === 1) {
            $randomSecret = bin2hex(random_bytes(4));
            $args[] = new Arg(new String_($randomSecret));
        }

        $args[] = new Arg(new ClassConstFetch(
            new FullyQualified('TYPO3\CMS\Core\Crypto\HashAlgo'),
            new Identifier('SHA3_256')
        ));

        $node->args = $args;

        return $node;
    }
}
