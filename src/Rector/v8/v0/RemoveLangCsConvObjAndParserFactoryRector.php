<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73482-LANG-csConvObjAndLANG-parserFactory.html
 */
final class RemoveLangCsConvObjAndParserFactoryRector extends AbstractRector
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
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, PropertyFetch::class];
    }

    /**
     * @param MethodCall|PropertyFetch $node
     */
    public function refactor($node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($this->isLanguageServiceCall($node)) {
            return $this->refactorLanguageServiceCall($node);
        }

        return $this->refactorAbstractPluginCall($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Remove CsConvObj and ParserFactory from LanguageService::class and $GLOBALS[\'lang\']',
            [
                new CodeSample(<<<'PHP'
$languageService = GeneralUtility::makeInstance(LanguageService::class);
$charsetConverter = $languageService->csConvObj;
$Localization = $languageService->parserFactory();
$charsetConverterGlobals = $GLOBALS['LANG']->csConvObj;
$LocalizationGlobals = $GLOBALS['LANG']->parserFactory();
PHP
                    , <<<'PHP'
$languageService = GeneralUtility::makeInstance(LanguageService::class);
$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
$Localization = GeneralUtility::makeInstance(LocalizationFactory::class);
$charsetConverterGlobals = GeneralUtility::makeInstance(CharsetConverter::class);
$LocalizationGlobals = GeneralUtility::makeInstance(LocalizationFactory::class);
PHP
                ),
            ]
        );
    }

    /**
     * @param MethodCall|PropertyFetch $node
     */
    private function shouldSkip($node): bool
    {
        if ($this->isLanguageServiceCall($node)) {
            return false;
        }

        if ($node instanceof PropertyFetch) {
            return true;
        }

        return ! $this->isMethodStaticCallOrClassMethodObjectType($node, AbstractPlugin::class);
    }

    private function isLanguageServiceCall(Node $node): bool
    {
        if (! (property_exists($node, 'var') && null !== $node->var)) {
            return false;
        }

        if ($this->isObjectType($node->var, LanguageService::class)) {
            return true;
        }

        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals($node, 'LANG')) {
            return true;
        }

        return $this->typo3NodeResolver->isAnyMethodCallOnGlobals($node, Typo3NodeResolver::LANG);
    }

    private function refactorLanguageServiceCall(Node $node): ?StaticCall
    {
        if (! (property_exists($node, 'name') && null !== $node->name)) {
            return null;
        }

        if (null === $this->getName($node->name)) {
            return null;
        }

        if ('csConvObj' === $this->getName($node->name)) {
            return $this->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->createClassConstantReference(CharsetConverter::class),
            ]);
        }

        if ($this->isName($node->name, 'parserFactory')) {
            return $this->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->createClassConstantReference(LocalizationFactory::class),
            ]);
        }

        return null;
    }

    private function refactorAbstractPluginCall(Node $node): ?Node
    {
        return $this->refactorLanguageServiceCall($node);
    }
}
