<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Charset\CharsetConverter;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.5/Deprecation-78670-DeprecatedCharsetConverterMethods.html
 */
final class CharsetConverterToMultiByteFunctionsRector extends AbstractRector
{
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

        switch ($this->getName($node->name)) {
            case 'strlen':
                return $this->toMultiByteStrlen($node);
            case 'convCapitalize':
                return $this->toMultiByteConvertCase($node);
            case 'substr':
                return $this->toMultiByteSubstr($node);
            case 'conv_case':
                return $this->toMultiByteLowerUpperCase($node);
            case 'utf8_strpos':
                return $this->toMultiByteStrPos($node);
            case 'utf8_strrpos':
                return $this->toMultiByteStrrPos($node);
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Move from CharsetConverter methods to mb_string functions', [
            new CodeSample(<<<'PHP'
        use TYPO3\CMS\Core\Charset\CharsetConverter;
        use TYPO3\CMS\Core\Utility\GeneralUtility;
        $charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
        $charsetConverter->strlen('utf-8', 'string');
PHP
                , <<<'PHP'
mb_strlen('string', 'utf-8');
PHP
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, CharsetConverter::class)) {
            return true;
        }
        return ! $this->isNames($node->name, [
            'strlen',
            'convCapitalize',
            'substr',
            'conv_case',
            'utf8_strpos',
            'utf8_strrpos',
        ]);
    }

    private function toMultiByteConvertCase(MethodCall $node): FuncCall
    {
        return $this->createFuncCall(
            'mb_convert_case',
            [$node->args[1], new ConstFetch(new Name('MB_CASE_TITLE')), $node->args[0]]
        );
    }

    private function toMultiByteSubstr(MethodCall $node): FuncCall
    {
        $start = $node->args[2] ?? $this->createArg(0);
        $length = $node->args[3] ?? $this->createNull();

        return $this->createFuncCall('mb_substr', [$node->args[1], $start, $length, $node->args[0]]);
    }

    private function toMultiByteLowerUpperCase(MethodCall $node): FuncCall
    {
        $methodCall = 'toLower' === $this->getValue($node->args[2]->value) ? 'mb_strtolower' : 'mb_strtoupper';

        return $this->createFuncCall($methodCall, [$node->args[1], $node->args[0]]);
    }

    private function toMultiByteStrPos(MethodCall $node): FuncCall
    {
        $offset = $node->args[2] ?? $this->createArg(0);

        return $this->createFuncCall(
            'mb_strpos',
            [$node->args[0], $node->args[1], $offset, $this->createArg('utf-8')]
        );
    }

    private function toMultiByteStrrPos(MethodCall $node): FuncCall
    {
        return $this->createFuncCall('mb_strrpos', [$node->args[0], $node->args[1], $this->createArg('utf-8')]);
    }

    private function toMultiByteStrlen(MethodCall $node): FuncCall
    {
        return $this->createFuncCall('mb_strlen', array_reverse($node->args));
    }
}
