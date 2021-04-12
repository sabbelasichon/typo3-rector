<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.5/Deprecation-86486-TypoScriptFrontendController-processOutput.html
 */
final class RefactorProcessOutputRector extends AbstractRector
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
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->typo3NodeResolver->isMethodCallOnGlobals(
            $node,
            'processOutput',
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            $this->refactorToNewMethodCalls($node);

            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(TypoScriptFrontendController::class)
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'processOutput')) {
            return null;
        }

        $this->refactorToNewMethodCalls($node);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'TypoScriptFrontendController->processOutput() to TypoScriptFrontendController->applyHttpHeadersToResponse() and TypoScriptFrontendController->processContentForOutput()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfe->processOutput();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfe->applyHttpHeadersToResponse();
$tsfe->processContentForOutput();
CODE_SAMPLE
                ),
            ]
        );
    }

    private function refactorToNewMethodCalls(MethodCall $node): void
    {
        $node->name = new Identifier('applyHttpHeadersToResponse');
        $newNode = $this->nodeFactory->createMethodCall($node->var, 'processContentForOutput');
        $this->addNodeAfterNode($newNode, $node);
    }
}
