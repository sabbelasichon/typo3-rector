<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BitwiseAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Core\Environment;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85285-DeprecatedSystemConstants.html
 */
final class ConstantToEnvironmentCallRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private const ALLOWED_NAMES = ['TYPO3_REQUESTTYPE_CLI', 'TYPO3_REQUESTTYPE'];

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns defined constant to static method call of new Environment API.', [
            new CodeSample('PATH_thisScript;', 'Environment::getCurrentScript();'),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class, BitwiseAnd::class];
    }

    /**
     * @param ConstFetch|BitwiseAnd $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ConstFetch) {
            return $this->refactorConstants($node);
        }

        if (! $node->left instanceof ConstFetch || ! $node->right instanceof ConstFetch) {
            return null;
        }
        if (! $this->isNames($node->left, self::ALLOWED_NAMES) || ! $this->isNames($node->right, self::ALLOWED_NAMES)) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(Environment::class, 'isCli');
    }

    private function refactorConstants(ConstFetch $node): ?Node
    {
        $constantName = $this->getName($node);
        if (null === $constantName) {
            return null;
        }

        if (! in_array(
            $constantName,
            ['PATH_thisScript', 'PATH_site', 'PATH_typo3', 'PATH_typo3conf', 'TYPO3_OS'],
            false
        )) {
            return null;
        }

        $property = $this->betterNodeFinder->findFirstAncestorInstanceOf($node, Property::class);

        if (null !== $property) {
            return null;
        }

        switch ($constantName) {
            case 'PATH_thisScript':
                return $this->nodeFactory->createStaticCall(Environment::class, 'getCurrentScript');
            case 'PATH_site':
                return new Concat($this->nodeFactory->createStaticCall(
                    Environment::class,
                    'getPublicPath'
                ), new String_('/'));
            case 'PATH_typo3':
                return $this->nodeFactory->createStaticCall(Environment::class, 'getBackendPath');
            case 'PATH_typo3conf':
                return $this->nodeFactory->createStaticCall(Environment::class, 'getLegacyConfigPath');
            case 'TYPO3_OS':
                return new BooleanOr($this->nodeFactory->createStaticCall(
                    Environment::class,
                    'isUnix'
                ), $this->nodeFactory->createStaticCall(Environment::class, 'isWindows'));
        }

        return null;
    }
}
