<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-102763-ExtbaseHashService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\MigrateExtbaseHashServiceToUseCoreHashServiceRectorTest
 */
final class MigrateExtbaseHashServiceToUseCoreHashServiceRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the class HashService from extbase to the one from TYPO3 core', [
            new CodeSample(
                <<<'CODE_SAMPLE'
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node)
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node->var,
            new ObjectType('TYPO3\\CMS\\Core\\Crypto\\HashService')
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['hmac', 'validateHmac', 'appendHmac', 'validateAndStripHmac'])) {
            return null;
        }

        if (count($node->args) > 1 && ! $this->isName($node->name, 'validateHmac')) {
            return null;
        }

        if (count($node->args) > 2 && $this->isName($node->name, 'validateHmac')) {
            return null;
        }

        $additionalSecretArgument = $this->nodeFactory->createArg('myAdditionalSecret');

        if ($this->isName($node->name, 'validateHmac')) {
            $node->args[2] = $node->args[1];
            $node->args[1] = $additionalSecretArgument;
        } else {
            $node->args[1] = $additionalSecretArgument;
        }

        return $node;
    }
}
