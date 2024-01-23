<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85543-Language-relatedPropertiesInTypoScriptFrontendControllerAndPageRepository.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseLanguageAspectForTsfeLanguagePropertiesRector\UseLanguageAspectForTsfeLanguagePropertiesRectorTest
 */
final class UseLanguageAspectForTsfeLanguagePropertiesRector extends AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const NODE_NAME_MAPPING = [
        'sys_language_uid' => 'id',
        'sys_language_content' => 'contentId',
        'sys_language_contentOL' => 'legacyOverlayType',
        'sys_language_mode' => 'legacyLanguageMode',
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
        return [Assign::class, Node\Stmt\Return_::class];
    }

    /**
     * @param Assign|Node\Stmt\Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $propertyFetch = $node->expr;

        if (! $propertyFetch instanceof PropertyFetch) {
            return null;
        }

        if ($this->shouldSkip($propertyFetch)) {
            return null;
        }

        if (! $this->isNames(
            $propertyFetch->name,
            ['sys_language_uid', 'sys_language_content', 'sys_language_contentOL', 'sys_language_mode']
        )) {
            return null;
        }

        // Check if we have an assigment to the property, if so do not change it
        if ($node instanceof Assign && $node->var instanceof PropertyFetch) {
            return null;
        }

        $nodeName = $this->getName($propertyFetch->name);

        if ($nodeName === null) {
            return null;
        }

        $property = self::NODE_NAME_MAPPING[$nodeName];

        $node->expr = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Context\Context'),
            ]),
            'getPropertyFromAspect',
            ['language', $property]
        );

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use LanguageAspect instead of language properties of TSFE', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$languageUid = $GLOBALS['TSFE']->sys_language_uid;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$languageUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        if ($this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        )) {
            return false;
        }

        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isPropertyFetchOnParentVariableOfTypeTypoScriptFrontendController(
            $propertyFetch
        );
    }
}
