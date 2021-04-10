<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Utility\EidUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85878-EidUtilityAndVariousTSFEMethods.html
 */
final class RemoveMethodsFromEidUtilityAndTsfeRector extends AbstractRector
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
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class, MethodCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($this->isEidUtilityMethodCall($node)) {
            $this->removeNode($node);

            return null;
        }

        if (! $this->isNames(
            $node->name,
            [
                'initFEuser',
                'storeSessionData',
                'previewInfo',
                'hook_eofe',
                'addTempContentHttpHeaders',
                'sendCacheHeaders',
            ]
        )) {
            return null;
        }

        $this->removeNode($node);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove EidUtility and various TSFE methods', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Frontend\Utility\EidUtility;
EidUtility::initExtensionTCA('foo');
EidUtility::initFeUser();
EidUtility::initLanguage();
EidUtility::initTCA();
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
''
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param StaticCall|MethodCall $node
     */
    private function shouldSkip(Node $node): bool
    {
        if ($this->isEidUtilityMethodCall($node)) {
            return false;
        }

        return ! $this->isMethodCallOnTsfe($node);
    }

    /**
     * @param StaticCall|MethodCall $node
     */
    private function isEidUtilityMethodCall(Node $node): bool
    {
        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(EidUtility::class)
        );
    }

    private function isMethodCallOnTsfe(Node $node): bool
    {
        if ($this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return true;
        }
        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(TypoScriptFrontendController::class)
        );
    }
}
