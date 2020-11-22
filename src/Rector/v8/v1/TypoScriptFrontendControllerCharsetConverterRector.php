<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.1/Deprecation-75327-TSFE-csConvObjAndTSFE-csConv.html
 */
final class TypoScriptFrontendControllerCharsetConverterRector extends AbstractRector
{
    /**
     * @var string
     */
    private const CHARSET_CONVERTER = 'charsetConverter';

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($this->isName($node->name, 'csConv')) {
            return $this->refactorMethodCsConv($node);
        }

        return $this->refactorCsConvObj($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor $TSFE->csConvObj and $TSFE->csConv()', [
            new CodeSample(<<<'PHP'
$output = $GLOBALS['TSFE']->csConvObj->conv_case('utf-8', 'foobar', 'lower');
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Charset\CharsetConverter;
$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
$output = $charsetConverter->conv_case('utf-8', 'foobar', 'lower');
PHP
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->typo3NodeResolver->isMethodCallOnGlobals(
            $node,
            'csConv',
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        if ($this->isMethodStaticCallOrClassMethodObjectType($node, TypoScriptFrontendController::class)) {
            return false;
        }
        return ! $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'csConvObj'
        );
    }

    private function refactorMethodCsConv(MethodCall $node): Node
    {
        $from = isset($node->args[1]) ? $this->getValue($node->args[1]->value) : null;

        if ('' === $from || 'null' === $from || null === $from) {
            return $node->args[0]->value;
        }

        $this->addCharsetConverterNode($node);

        return $this->createMethodCall(self::CHARSET_CONVERTER, 'conv', [
            $node->args[0],
            $this->createMethodCall(self::CHARSET_CONVERTER, 'parse_charset', [$node->args[1]]),
            'utf-8',
        ]);
    }

    private function addCharsetConverterNode(MethodCall $node): void
    {
        $charsetConverterNode = new Expression(
            new Assign(
                new Variable(self::CHARSET_CONVERTER),
                $this->createStaticCall(
                    GeneralUtility::class,
                    'makeInstance',
                    [$this->createClassConstantReference(CharsetConverter::class)]
                )
            )
        );
        $this->addNodeBeforeNode($charsetConverterNode, $node);
    }

    private function refactorCsConvObj(MethodCall $node): ?Node
    {
        $this->addCharsetConverterNode($node);

        $methodName = $this->getName($node->name);
        if (null === $methodName) {
            return null;
        }

        return $this->createMethodCall(self::CHARSET_CONVERTER, $methodName, $node->args);
    }
}
