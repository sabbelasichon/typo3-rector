<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeVisitor;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\CodeQuality\General\RemoveTypo3VersionChecksRector\RemoveTypo3VersionChecksRectorTest
 */
final class RemoveTypo3VersionChecksRector extends AbstractRector implements NoChangelogRequiredInterface, DocumentedRuleInterface, ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const TARGET_VERSION = 'target_version';

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    private ?int $targetVersion = null;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove Typo3Version checks for older TYPO3 versions',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
if ((new Typo3Version())->getMajorVersion() >= 13) {
    // do something for TYPO3 13 and above
    $this->request->getAttribute('frontend.cache.collector')->addCacheTags(new CacheTag('tx_extension'));
} else {
    // do something for older versions
    $typoScriptFrontendController->addCacheTags(['tx_extension']);
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
// do something for TYPO3 13 and above
$this->request->getAttribute('frontend.cache.collector')->addCacheTags(new CacheTag('tx_extension'));
CODE_SAMPLE
                    ,
                    [
                        self::TARGET_VERSION => 13,
                    ]
                ),
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
$iconSize = (new Typo3Version())->getMajorVersion() >= 13 ? IconSize::SMALL : 'small';
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$iconSize = IconSize::SMALL;
CODE_SAMPLE
                    ,
                    [
                        self::TARGET_VERSION => 13,
                    ]
                )]
        );
    }

    /**
     * @param array<string, int> $configuration
     */
    public function configure(array $configuration): void
    {
        $targetVersion = $configuration[self::TARGET_VERSION] ?? null;
        Assert::integer($targetVersion);
        $this->targetVersion = $targetVersion;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [If_::class, Ternary::class];
    }

    /**
     * @param If_|Ternary $node
     */
    public function refactor(Node $node)
    {
        // If no version is configured, we cannot safely remove code
        if ($this->targetVersion === null) {
            return null;
        }

        if ($node instanceof If_) {
            return $this->refactorIf($node);
        }

        return $this->refactorTernary($node);
    }

    /**
     * @return Node|Node[]|int|null
     */
    private function refactorIf(If_ $if)
    {
        if (! $if->cond instanceof BinaryOp) {
            return null;
        }

        $evaluation = $this->evaluateCondition($if->cond);

        if ($evaluation === null) {
            return null;
        }

        if ($evaluation) {
            // Condition is TRUE (e.g. version >= 13), keep the IF statements
            return $if->stmts;
        }

        // Condition is FALSE (e.g. version < 13)
        if ($if->else instanceof Else_) {
            // Keep the ELSE statements
            return $if->else->stmts;
        }

        // Condition is FALSE and no ELSE exists, remove the whole block
        return NodeVisitor::REMOVE_NODE;
    }

    private function refactorTernary(Ternary $ternary): ?Node
    {
        if (! $ternary->cond instanceof BinaryOp) {
            return null;
        }

        $evaluation = $this->evaluateCondition($ternary->cond);

        if ($evaluation === null) {
            return null;
        }

        if ($evaluation) {
            return $ternary->if;
        }

        return $ternary->else;
    }

    private function evaluateCondition(BinaryOp $binaryOp): ?bool
    {
        $comparedVersion = null;
        $isVersionLeft = false;

        // Identify which side is the Typo3Version call and which is the integer
        if ($this->isTypo3VersionCall($binaryOp->left)) {
            $comparedVersion = $this->valueResolver->getValue($binaryOp->right);
            $isVersionLeft = true;
        } elseif ($this->isTypo3VersionCall($binaryOp->right)) {
            $comparedVersion = $this->valueResolver->getValue($binaryOp->left);
            $isVersionLeft = false;
        }

        if (! is_int($comparedVersion)) {
            return null;
        }

        // GUARD CLAUSE: Forward Compatibility
        // If the code is checking against a version HIGHER than our target (e.g. checking for v14 while on v13),
        // we must NOT touch it. We only refactor historical checks (<= target).
        if ($comparedVersion > $this->targetVersion) {
            return null;
        }

        $target = $this->targetVersion;

        // Normalize logic: We want to check if the code condition evaluates to TRUE
        // given that the ACTUAL version is $target.

        if ($isVersionLeft) {
            // Code: getMajorVersion() [OP] 13
            // Logic: $target [OP] $comparedVersion
            if ($binaryOp instanceof GreaterOrEqual) {
                return $target >= $comparedVersion;
            }

            if ($binaryOp instanceof Greater) {
                return $target > $comparedVersion;
            }

            if ($binaryOp instanceof SmallerOrEqual) {
                return $target <= $comparedVersion;
            }

            if ($binaryOp instanceof Smaller) {
                return $target < $comparedVersion;
            }

            if ($binaryOp instanceof Equal || $binaryOp instanceof Identical) {
                return $target === $comparedVersion;
            }
        } else {
            // Code: 13 [OP] getMajorVersion()
            // Logic: $comparedVersion [OP] $target
            if ($binaryOp instanceof GreaterOrEqual) {
                return $comparedVersion >= $target;
            }

            if ($binaryOp instanceof Greater) {
                return $comparedVersion > $target;
            }

            if ($binaryOp instanceof SmallerOrEqual) {
                return $comparedVersion <= $target;
            }

            if ($binaryOp instanceof Smaller) {
                return $comparedVersion < $target;
            }

            if ($binaryOp instanceof Equal || $binaryOp instanceof Identical) {
                return $comparedVersion === $target;
            }
        }

        return null;
    }

    private function isTypo3VersionCall(Node $node): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $node->var instanceof New_) {
            return false;
        }

        if (! $this->isName($node->name, 'getMajorVersion')) {
            return false;
        }

        return $this->isName($node->var->class, 'TYPO3\CMS\Core\Information\Typo3Version');
    }
}
