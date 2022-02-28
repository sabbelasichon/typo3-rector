<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.1/Deprecation-75621-GeneralUtilityMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v1\RefactorVariousGeneralUtilityMethodsRector\RefactorVariousGeneralUtilityMethodsRectorTest
 */
final class RefactorVariousGeneralUtilityMethodsRector extends AbstractRector
{
    /**
     * @var string
     */
    private const COMPAT_VERSION = 'compat_version';

    /**
     * @var string
     */
    private const CONVERT_MICROTIME = 'convertMicrotime';

    /**
     * @var string
     */
    private const RAW_URL_ENCODE_JS = 'rawUrlEncodeJS';

    /**
     * @var string
     */
    private const RAW_URL_ENCODE_FP = 'rawUrlEncodeFP';

    /**
     * @var string
     */
    private const GET_MAXIMUM_PATH_LENGTH = 'getMaximumPathLength';

    /**
     * @var string
     */
    private const LCFIRST = 'lcfirst';

    /**
     * @var string
     */
    private const PARTS = 'parts';

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility')
        )) {
            return null;
        }

        if (! $this->isNames($node->name, [
            self::COMPAT_VERSION,
            self::CONVERT_MICROTIME,
            self::RAW_URL_ENCODE_JS,
            self::RAW_URL_ENCODE_FP,
            self::LCFIRST,
            self::GET_MAXIMUM_PATH_LENGTH,
        ])) {
            return null;
        }

        $nodeName = $this->getName($node->name);

        if (self::COMPAT_VERSION === $nodeName) {
            return new GreaterOrEqual(
                $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\VersionNumberUtility',
                    'convertVersionNumberToInteger',
                    [new ConstFetch(new Name('TYPO3_branch'))]
                ),
                $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\VersionNumberUtility',
                    'convertVersionNumberToInteger',
                    $node->args
                )
            );
        }

        if (self::CONVERT_MICROTIME === $nodeName) {
            $funcCall = $this->nodeFactory->createFuncCall('explode', [new String_(' '), $node->args[0]->value]);
            $this->nodesToAddCollector->addNodeBeforeNode(
                new Expression(new Assign(new Variable(self::PARTS), $funcCall)),
                $node
            );

            return $this->nodeFactory->createFuncCall('round', [
                new Mul(new Plus(
                    new ArrayDimFetch(new Variable(self::PARTS), new LNumber(0)),
                    new ArrayDimFetch(new Variable(self::PARTS), new LNumber(1))
                ), new LNumber(1000)),
            ]);
        }

        if (self::RAW_URL_ENCODE_JS === $nodeName) {
            return $this->nodeFactory->createFuncCall('str_replace', [
                '%20',
                ' ',
                $this->nodeFactory->createFuncCall('rawurlencode', $node->args),
            ]);
        }

        if (self::RAW_URL_ENCODE_FP === $nodeName) {
            return $this->nodeFactory->createFuncCall('str_replace', [
                '%2F',
                '/',
                $this->nodeFactory->createFuncCall('rawurlencode', $node->args),
            ]);
        }

        if (self::LCFIRST === $nodeName) {
            return $this->nodeFactory->createFuncCall(self::LCFIRST, $node->args);
        }

        if (self::GET_MAXIMUM_PATH_LENGTH === $nodeName) {
            return new ConstFetch(new Name('PHP_MAXPATHLEN'));
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor various deprecated methods of class GeneralUtility', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
$url = 'https://www.domain.com/';
$url = GeneralUtility::rawUrlEncodeFP($url);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$url = 'https://www.domain.com/';
$url = str_replace('%2F', '/', rawurlencode($url));
CODE_SAMPLE
            ),
        ]);
    }
}
