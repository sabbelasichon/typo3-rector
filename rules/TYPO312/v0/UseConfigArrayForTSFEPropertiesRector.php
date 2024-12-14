<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97866-VariousPublicTSFEProperties.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector\UseConfigArrayForTSFEPropertiesRectorTest
 */
final class UseConfigArrayForTSFEPropertiesRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string[]
     */
    private const DEPRECATED_PUBLIC_PROPERTIES = [
        'intTarget',
        'extTarget',
        'fileTarget',
        'spamProtectEmailAddresses',
        'baseUrl',
    ];

    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
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

        if (! $this->nodeNameResolver->isNames($node->name, self::DEPRECATED_PUBLIC_PROPERTIES)) {
            return null;
        }

        $propertyName = $this->nodeNameResolver->getName($node->name);

        if ($propertyName === null) {
            return null;
        }

        return new ArrayDimFetch(new ArrayDimFetch(
            new PropertyFetch($node->var, 'config'),
            new String_('config')
        ), new String_($propertyName));
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use config array of TSFE instead of properties', [new CodeSample(
            <<<'CODE_SAMPLE'
$fileTarget = $GLOBALS['TSFE']->fileTarget;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$fileTarget = $GLOBALS['TSFE']->config['config']['fileTarget'];
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(PropertyFetch $node): bool
    {
        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->isObjectType(
            $node->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }
}
