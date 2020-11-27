<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;
use TYPO3\CMS\Lang\LanguageService;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.2/Deprecation-71917-DeprecateTheArgumentHscForGetLLGetLLLAndSL.html
 */
final class UseHtmlSpecialCharsDirectlyForTranslationRector extends AbstractRector
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
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('htmlspecialchars directly to properly escape the content.', [
            new CodeSample(<<<'PHP'
PHP
                , <<<'PHP'
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($this->isLanguageServiceCall($node)) {
            return $this->refactorLanguageServiceCall($node);
        }

        return $this->refactorAbstractPluginCall($node);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->isLanguageServiceCall($node)) {
            return false;
        }

        return ! $this->isMethodStaticCallOrClassMethodObjectType($node, AbstractPlugin::class);
    }

    private function isLanguageServiceCall(MethodCall $node): bool
    {
        if ($this->isMethodStaticCallOrClassMethodObjectType($node, LanguageService::class)) {
            return true;
        }
        return $this->typo3NodeResolver->isAnyMethodCallOnGlobals($node, Typo3NodeResolver::LANG);
    }

    private function refactorAbstractPluginCall(MethodCall $node): ?Node
    {
        if (! $this->isName($node->name, 'pi_getLL')) {
            return null;
        }

        return $this->refactorToHtmlSpecialChars($node, 2);
    }

    private function refactorLanguageServiceCall(MethodCall $node): ?Node
    {
        if (! $this->isNames($node->name, ['sL', 'getLL', 'getLLL'])) {
            return null;
        }

        if ($this->isName($node->name, 'getLLL')) {
            return $this->refactorToHtmlSpecialChars($node, 2);
        }

        return $this->refactorToHtmlSpecialChars($node, 1);
    }

    private function refactorToHtmlSpecialChars(MethodCall $node, int $argumentPosition): ?Node
    {
        if (! isset($node->args[$argumentPosition])) {
            return null;
        }

        $hsc = $this->getValue($node->args[$argumentPosition]->value);

        if (null === $hsc) {
            return null;
        }

        // If you don´t unset it you will end up in an infinite loop here
        unset($node->args[$argumentPosition]);

        if (false === $hsc) {
            return null;
        }

        return $this->createFuncCall('htmlspecialchars', [$node]);
    }
}
