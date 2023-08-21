<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97866-VariousPublicTSFEProperties.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector\UseConfigArrayForTSFEPropertiesRectorTest
 */
final class UseConfigArrayForTSFEPropertiesRector extends AbstractRector
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

        if (null === $propertyName) {
            return null;
        }

        return new ArrayDimFetch(new ArrayDimFetch(
            new PropertyFetch($node->var, 'config'),
            new String_('config')
        ), new String_($propertyName));
    }

    /**
     * @codeCoverageIgnore
     */
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
