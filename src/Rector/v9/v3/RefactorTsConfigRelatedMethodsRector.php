<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.3/Deprecation-84993-DeprecateSomeTSconfigRelatedMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v3\RefactorTsConfigRelatedMethodsRector\RefactorTsConfigRelatedMethodsRectorTest
 */
final class RefactorTsConfigRelatedMethodsRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor TSconfig related methods', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$hasFilterBox = !$GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.hideFilter');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$hasFilterBox = !($GLOBALS['BE_USER']->getTSConfig()['options.']['pageTree.']['hideFilter.'] ?? null);
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isNames($node->name, ['getTSConfigVal', 'getTSConfigProp', 'getTSConfig'])) {
            return null;
        }

        if (! isset($node->args[0])) {
            return null;
        }

        $value = $this->valueResolver->getValue($node->args[0]->value);

        if ($value === null) {
            return null;
        }

        if (! is_string($value) || $value === '') {
            return null;
        }

        $configuration = $this->createConfiguration($value);

        $newArrayDimFetch = $this->nodeFactory->createMethodCall($node->var, 'getTSConfig');

        $defaultValueNode = $this->nodeFactory->createNull();

        foreach ($configuration as $key) {
            $newArrayDimFetch = new ArrayDimFetch($newArrayDimFetch, new String_(sprintf('%s.', $key)));
        }

        return new Coalesce($newArrayDimFetch, $defaultValueNode);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULL_COALESCE;
    }

    /**
     * @param MethodCall $node
     */
    private function shouldSkip(Node $node): bool
    {
        if ($this->typo3NodeResolver->isMethodCallOnBackendUser($node)) {
            return false;
        }

        return ! $this->isObjectType(
            $node->var,
            new ObjectType('TYPO3\CMS\Core\Authentication\BackendUserAuthentication')
        );
    }

    /**
     * @return string[]
     */
    private function createConfiguration(string $objectString): array
    {
        return explode('.', $objectString);
    }
}
