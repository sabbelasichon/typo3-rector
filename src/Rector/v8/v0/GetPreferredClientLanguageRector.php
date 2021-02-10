<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73511-BrowserLanguageDetectionMovedToLocales.html
 */
final class GetPreferredClientLanguageRector extends AbstractRector
{
    /**
     * @var string
     */
    private const GET_PREFERRED_CLIENT_LANGUAGE = 'getPreferredClientLanguage';

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isCharsetConverterMethodCall($node) && ! $this->isCallFromTypoScriptFrontendController($node)) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->nodeFactory->createClassConstReference(Locales::class)]
            ),
            self::GET_PREFERRED_CLIENT_LANGUAGE,
            $node->args
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use Locales->getPreferredClientLanguage() instead of CharsetConverter::getPreferredClientLanguage()',
            [
                new CodeSample(
                    <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
$preferredLanguage = $GLOBALS['TSFE']->csConvObj->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$preferredLanguage = GeneralUtility::makeInstance(Locales::class)->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
PHP
                ),

            ]);
    }

    private function isCharsetConverterMethodCall(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, CharsetConverter::class)) {
            return false;
        }
        return $this->isName($node->name, self::GET_PREFERRED_CLIENT_LANGUAGE);
    }

    private function isCallFromTypoScriptFrontendController(MethodCall $node): bool
    {
        if (! $node->var instanceof PropertyFetch) {
            return false;
        }
        return $this->isName($node->name, self::GET_PREFERRED_CLIENT_LANGUAGE);
    }
}
