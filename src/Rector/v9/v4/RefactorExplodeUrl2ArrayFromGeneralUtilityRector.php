<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85801-GeneralUtilityexplodeUrl2Array-2ndMethodArgument.html
 */
final class RefactorExplodeUrl2ArrayFromGeneralUtilityRector extends AbstractRector
{
    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($call, GeneralUtility::class)) {
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
        if ($this->isFalse($lastArgument->value)) {
            $call->args = $arguments;
            return null;
        }
        return $this->createFuncCall('parse_str', [$arguments[0], $node->var]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function parse_str if it is true',
            [
                new CodeSample(<<<'PHP'
$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
PHP
, <<<'PHP'
parse_str('https://www.domain.com', $variable);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
PHP
),
            ]
        );
    }
}
