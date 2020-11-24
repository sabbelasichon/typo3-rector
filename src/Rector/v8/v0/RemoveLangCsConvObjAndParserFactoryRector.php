<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73482-LANG-csConvObjAndLANG-parserFactory.html
 */
final class RemoveLangCsConvObjAndParserFactoryRector extends AbstractRector
{
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, LanguageService::class)) {
            return null;
        }

        if ($this->isName($node->name, 'csConvObj')) {
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

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Remove CsConvObj and ParserFactory from LanguageService::class',
            [
                new CodeSample(<<<'PHP'
$languageService = GeneralUtility::makeInstance(LanguageService::class);
$charsetConverter = $languageService->csConvObj();
$Localization = $languageService->parserFactory();
PHP
                    , <<<'PHP'
$languageService = GeneralUtility::makeInstance(LanguageService::class);
$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
$Localization = GeneralUtility::makeInstance(LocalizationFactory::class);
PHP
                ),
            ]
        );
    }
}
