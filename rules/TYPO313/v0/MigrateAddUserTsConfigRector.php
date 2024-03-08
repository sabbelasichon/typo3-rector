<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-101807-ExtensionManagementUtilityaddUserTSConfig.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTsConfigRector\MigrateAddUserTsConfigRectorTest
 */
final class MigrateAddUserTsConfigRector extends AbstractRector
{
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'migrate ExtensionManagementUtility::addUserTSConfig() to Configuration/user.tsconfig',
            [
            new CodeSample(
                <<<'CODE_SAMPLE'
$boot = function ($extKey) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        '@import \'EXT:' . $extKey . '/Configuration/TsConfig/User/user.tsconfig\''
    );
};

$boot('testpackage');
unset($boot);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$boot = function ($extKey) {
};

$boot('testpackage');
unset($boot);
CODE_SAMPLE
            ),
        
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     */
    public function refactor(Node $node)
    {
        $call = $node->expr;

        if ($this->shouldSkip($call)) {
            return null;
        }

        $arg = $call->args[0] ?? null;
        if (empty($arg)) {
            return null;
        }

        $content = $this->valueResolver->getValue($arg->value);

        return NodeTraverser::REMOVE_NODE;
    }

    /**
     * @param mixed $call
     */
    private function shouldSkip($call): bool
    {
        if (! $call instanceof StaticCall) {
            return true;
        }

        $object = new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility');
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($call, $object)) {
            return true;
        }

        if (! $this->nodeNameResolver->isName($call->name, 'addUserTSConfig')) {
            return true;
        }

        if ($call->args === []) {
            return true;
        }
        return ! $call->args[0] instanceof Node\Arg;
    }
}
