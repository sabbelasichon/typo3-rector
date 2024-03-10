<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.5/Deprecation-95219-TypoScriptFrontendController-ATagParams.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector\ReplaceTSFEATagParamsCallOnGlobalsRectorTest
 */
final class ReplaceTSFEATagParamsCallOnGlobalsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(Typo3NodeResolver $typo3NodeResolver, Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $propertyFetch = $this->nodeFactory->createPropertyFetch($this->typo3GlobalsFactory->create('TSFE'), 'config');

        return new Coalesce(
            new ArrayDimFetch(new ArrayDimFetch($propertyFetch, new String_('config')), new String_('ATagParams')),
            new String_('')
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces all direct calls to $GLOBALS[\'TSFE\']->ATagParams.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$foo = $GLOBALS['TSFE']->ATagParams;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$foo = $GLOBALS['TSFE']->config['config']['ATagParams'] ?? '';
CODE_SAMPLE
            )]
        );
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        if (! $this->isName($propertyFetch->name, 'ATagParams')) {
            return true;
        }

        if ($this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
