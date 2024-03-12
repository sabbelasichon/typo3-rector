<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102875-ExpressionBuilderChanges.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExpressionBuilderTrimMethodSecondParameterRector\MigrateExpressionBuilderTrimMethodSecondParameterRectorTest
 */
final class MigrateExpressionBuilderTrimMethodSecondParameterRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var array<int, string>
     */
    private static array $integerToTrimMode = [
        0 => 'UNSPECIFIED',
        1 => 'LEADING',
        2 => 'TRAILING',
        3 => 'BOTH',
    ];

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate second parameter of trim method to enum', [new CodeSample(
            <<<'CODE_SAMPLE'
$queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
$queryBuilder->expr()->comparison(
    $queryBuilder->expr()->trim($fieldName, 1),
    ExpressionBuilder::EQ,
    $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
$queryBuilder->expr()->comparison(
    $queryBuilder->expr()->trim($fieldName, TrimMode::LEADING),
    ExpressionBuilder::EQ,
    $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
);
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'trim')) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder')
        )) {
            return null;
        }

        $secondArgument = $node->args[1] ?? null;

        if ($secondArgument === null) {
            return null;
        }

        $trimMode = $this->valueResolver->getValue($secondArgument->value);

        if (! is_int($trimMode)) {
            return null;
        }

        if (! isset(self::$integerToTrimMode[$trimMode])) {
            return null;
        }

        $trimModeConstant = self::$integerToTrimMode[$trimMode];

        $node->args[1]->value = $this->nodeFactory->createClassConstFetch(
            'Doctrine\\DBAL\\Platforms\\TrimMode',
            $trimModeConstant
        );

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }
}
