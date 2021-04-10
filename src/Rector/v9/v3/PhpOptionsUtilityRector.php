<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\PhpOptionsUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-85102-PhpOptionsUtility.html
 */
final class PhpOptionsUtilityRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(PhpOptionsUtility::class)
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['isSessionAutoStartEnabled', 'getIniValueBoolean'])) {
            return null;
        }

        $configOption = 'session.auto_start';
        if ($this->isName($node->name, 'getIniValueBoolean')) {
            $configOption = $this->valueResolver->getValue($node->args[0]->value);
        }

        return $this->nodeFactory->createFuncCall('filter_var', [
            $this->nodeFactory->createFuncCall('ini_get', [$configOption]),
            new ConstFetch(new Name('FILTER_VALIDATE_BOOLEAN')),
            new Array_([
                new ArrayItem(new ConstFetch(new Name('FILTER_REQUIRE_SCALAR'))),
                new ArrayItem(new ConstFetch(new Name('FILTER_NULL_ON_FAILURE'))),
            ]
            ),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor methods from PhpOptionsUtility', [
            new CodeSample(
                'PhpOptionsUtility::isSessionAutoStartEnabled()',
                "filter_var(ini_get('session.auto_start'), FILTER_VALIDATE_BOOLEAN, [FILTER_REQUIRE_SCALAR, FILTER_NULL_ON_FAILURE])"
            ),
        ]);
    }
}
