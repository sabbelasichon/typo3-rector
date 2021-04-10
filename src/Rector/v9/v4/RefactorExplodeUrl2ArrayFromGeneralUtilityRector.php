<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85801-GeneralUtilityexplodeUrl2Array-2ndMethodArgument.html
 */
final class RefactorExplodeUrl2ArrayFromGeneralUtilityRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof StaticCall && ! $node->expr instanceof MethodCall) {
            return null;
        }
        /** @var StaticCall|MethodCall $call */
        $call = $node->expr;
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $call,
            new ObjectType(GeneralUtility::class)
        )) {
            return null;
        }
        if (! $this->isName($call->name, 'explodeUrl2Array')) {
            return null;
        }
        $arguments = $call->args;
        if (count($arguments) <= 1) {
            return null;
        }
        /** @var Arg $lastArgument */
        $lastArgument = array_pop($arguments);
        if ($this->valueResolver->isFalse($lastArgument->value)) {
            $call->args = $arguments;
            return null;
        }
        return $this->nodeFactory->createFuncCall('parse_str', [$arguments[0], $node->var]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function parse_str if it is true',
            [
                new CodeSample(<<<'CODE_SAMPLE'
$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
parse_str('https://www.domain.com', $variable);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
CODE_SAMPLE
),
            ]
        );
    }
}
