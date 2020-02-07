<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82893-RemoveGlobalVariablePARSETIME_START.html
 */
final class SubstituteConstantParsetimeStartRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Expr::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->typo3NodeResolver->isTypo3Global($node, Typo3NodeResolver::ParsetimeStart)) {
            return null;
        }

        return $this->createFunction('round', [
            new Mul(new ArrayDimFetch(
                new ArrayDimFetch(
                    new Variable('GLOBALS'),
                    new String_('TYPO3_MISC')
                ),
                new String_('microtime_start')
            ), new LNumber(1000)),
        ]);
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Substitute $GLOBALS[\'PARSETIME_START\'] with round($GLOBALS[\'TYPO3_MISC\'][\'microtime_start\'] * 1000)', [
            new CodeSample(
                <<<'PHP'
$parseTime = $GLOBALS['PARSETIME_START'];
PHP
                ,
                <<<'PHP'
$parseTime = round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000);
PHP
            ),
        ]);
    }
}
