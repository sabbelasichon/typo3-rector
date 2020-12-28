<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\LNumber;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.4/Deprecation-91001-VariousMethodsWithinGeneralUtility.html
 */
final class SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector extends AbstractRector
{
    /**
     * @var array
     */
    private const METHOD_CALL_TO_REFACTOR = ['IPv6Hex2Bin', 'IPv6Bin2Hex', 'compressIPv6', 'milliseconds'];

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isNames($node->name, self::METHOD_CALL_TO_REFACTOR)) {
            return null;
        }

        switch ($node->name) {
            case 'IPv6Hex2Bin':
                return $this->createFuncCall('inet_pton', $node->args);
            case 'IPv6Bin2Hex':
                return $this->createFuncCall('inet_ntop', $node->args);
            case 'compressIPv6':
                return $this->createFuncCall('inet_ntop', [$this->createFuncCall('inet_pton', $node->args)]);
            case 'milliseconds':
                return $this->createFuncCall('round', [
                    new Mul($this->createFuncCall(
                        'microtime',
                        [$this->createArg($this->createTrue())]
                    ), new LNumber(1000)),
                ]);
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Substitute deprecated method calls of class GeneralUtility',
            [
                new CodeSample(
                    <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hex = '127.0.0.1';
GeneralUtility::IPv6Hex2Bin($hex);
$bin = $packed = chr(127) . chr(0) . chr(0) . chr(1);
GeneralUtility::IPv6Bin2Hex($bin);
$address = '127.0.0.1';
GeneralUtility::compressIPv6($address);
GeneralUtility::milliseconds();
PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hex = '127.0.0.1';
inet_pton($hex);
$bin = $packed = chr(127) . chr(0) . chr(0) . chr(1);
inet_ntop($bin);
$address = '127.0.0.1';
inet_ntop(inet_pton($address));
round(microtime(true) * 1000);
PHP
                ),
            ]
        );
    }
}
