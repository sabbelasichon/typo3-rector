<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97065-TYPO3FrontendAlwaysRenderedInUTF-8.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEMetaCharSetCallsRector\RemoveTSFEMetaCharSetCallsRectorTest
 */
final class RemoveTSFEMetaCharSetCallsRector extends AbstractRector implements DocumentedRuleInterface
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

        return new String_('utf-8');
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes calls to metaCharset property or methods of TSFE', [new CodeSample(
            <<<'CODE_SAMPLE'
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$foo = $GLOBALS['TSFE']->metaCharset;
$bar = $tsfe->metaCharset;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$foo = 'utf-8';
$bar = 'utf-8';
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        if (! $this->isName($propertyFetch->name, 'metaCharset')) {
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
