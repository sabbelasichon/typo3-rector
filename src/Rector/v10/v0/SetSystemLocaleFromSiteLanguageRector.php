<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-88473-TypoScriptFrontendController-settingLocale.html
 */
final class SetSystemLocaleFromSiteLanguageRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, TypoScriptFrontendController::class) &&
             ! $this->typo3NodeResolver->isAnyMethodCallOnGlobals(
                $node,
                Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
            )) {
            return null;
        }

        if (! $this->isName($node->name, 'settingLocale')) {
            return null;
        }

        return $this->createStaticCall(Locales::class, 'setSystemLocaleFromSiteLanguage', [
            $this->createMethodCall($node->var, 'getLanguage'),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Refactor TypoScriptFrontendController->settingLocale() to Locales::setSystemLocaleFromSiteLanguage()',
            [
                new CodeSample(
                    <<<'PHP'

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$controller = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, 0, 0);
$controller->settingLocale();
PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$controller = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, 0, 0);
Locales::setSystemLocaleFromSiteLanguage($controller->getLanguage());
PHP
                ),
            ]
        );
    }
}
