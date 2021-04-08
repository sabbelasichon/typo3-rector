<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\Cast\String_ as StringCast;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-84993-DeprecateSomeTSconfigRelatedMethods.html
 */
final class RefactorTsConfigRelatedMethodsRector extends AbstractRector
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
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor TSconfig related methods', [
            new CodeSample(<<<'CODE_SAMPLE'
$hasFilterBox = !$GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.hideFilter');
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
$hasFilterBox = !($GLOBALS['BE_USER']->getTSConfig()['options.']['pageTree.']['hideFilter.'] ?? null);
CODE_SAMPLE
            ),
        ]);
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return array<class-string<Node>>
=======
=======
>>>>>>> da7142f... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
>>>>>>> f7cbd4b... make PHPStan smarted on getNodeTypes()
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
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
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::NULL_COALESCE)) {
            return null;
        }

        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isNames($node->name, ['getTSConfigVal', 'getTSConfigProp', 'getTSConfig'])) {
            return null;
        }

        if (! isset($node->args[0])) {
            return null;
        }

        $value = $this->valueResolver->getValue($node->args[0]->value);

        if (null === $value) {
            return null;
        }

        if (! is_string($value) || '' === $value) {
            return null;
        }

        $configuration = $this->createConfiguration($value);

        $newNode = $this->nodeFactory->createMethodCall($node->var, 'getTSConfig');

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        $defaultValueNode = $this->nodeFactory->createNull();
        if ($parentNode instanceof Cast) {
            $defaultValueNode = $this->transformToSpecificCast($parentNode);
        }

        foreach ($configuration as $key) {
            $newNode = new ArrayDimFetch($newNode, new String_(sprintf('%s.', $key)));
        }

        return new Coalesce($newNode, $defaultValueNode);
    }

    /**
     * @param MethodCall $node
     */
    private function shouldSkip(Node $node): bool
    {
        if ($this->typo3NodeResolver->isMethodCallOnBackendUser($node)) {
            return false;
        }
        return ! $this->isObjectType($node->var, BackendUserAuthentication::class);
    }

    private function createConfiguration(string $objectString): array
    {
        return explode('.', $objectString);
    }

    private function transformToSpecificCast(Cast $node): Expr
    {
        if ($node instanceof Array_) {
            return $this->nodeFactory->createArray([]);
        }

        if ($node instanceof StringCast) {
            return new String_('');
        }

        if ($node instanceof Bool_) {
            return $this->nodeFactory->createFalse();
        }

        if ($node instanceof Int_) {
            return new LNumber(0);
        }

        return $this->nodeFactory->createNull();
    }
}
