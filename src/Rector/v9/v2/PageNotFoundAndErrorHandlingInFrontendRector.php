<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use TYPO3\CMS\Backend\Backend\Shortcut\ShortcutRepository;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.2/Deprecation-83883-PageNotFoundAndErrorHandlingInFrontend.html
 */
final class PageNotFoundAndErrorHandlingInFrontendRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor TypoScriptFrontendController pageNotFoundAndErrorHandling methods to a PSR-7-compliant response. The response still has to be completed, it is inserted into the variable $request.', [
            new CodeSample(<<<'PHP'
$tsfeController = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfeController->checkPageUnavailableHandler();
PHP
                , <<<'PHP'
$errorController = GeneralUtility::makeInstance(ErrorController::class);
$errorController->unavailableAction($request);
PHP
            ),
        ]);
    }

    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, TypoScriptFrontendController::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'checkPageUnavailableHandler')) {
            return null;
        }

        if ($this->isName($node->name, 'checkPageUnavailableHandler')) {
            $errorControllerVariable = new Variable('errorController');
            $assign = new Assign(
                $errorControllerVariable,
                $this->createStaticCall(
                    GeneralUtility::class,
                    'makeInstance',
                    [$this->createClassConstantReference(ErrorController::class)]
                )
            );

            $this->addNodeBeforeNode($assign, $node);

            return $this->createMethodCall($errorControllerVariable, 'unavailableAction', [new Variable('request')]);
        }

        return null;
    }
}
