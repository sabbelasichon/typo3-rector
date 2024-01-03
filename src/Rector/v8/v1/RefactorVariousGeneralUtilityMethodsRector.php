<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.1/Deprecation-75621-GeneralUtilityMethods.html
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
            self::RAW_URL_ENCODE_JS,
            self::RAW_URL_ENCODE_FP,
            self::LCFIRST,
            self::GET_MAXIMUM_PATH_LENGTH,
        ])) {
            return null;
        }

        $nodeName = $this->getName($node->name);

        if ($nodeName === self::COMPAT_VERSION) {
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

        if ($nodeName === self::RAW_URL_ENCODE_JS) {
            return $this->nodeFactory->createFuncCall('str_replace', [
                '%20',
                ' ',
                $this->nodeFactory->createFuncCall('rawurlencode', $node->args),
            ]);
        }

        if ($nodeName === self::RAW_URL_ENCODE_FP) {
            return $this->nodeFactory->createFuncCall('str_replace', [
                '%2F',
                '/',
                $this->nodeFactory->createFuncCall('rawurlencode', $node->args),
            ]);
        }

        if ($nodeName === self::LCFIRST) {
            return $this->nodeFactory->createFuncCall(self::LCFIRST, $node->args);
        }

        if ($nodeName === self::GET_MAXIMUM_PATH_LENGTH) {
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
