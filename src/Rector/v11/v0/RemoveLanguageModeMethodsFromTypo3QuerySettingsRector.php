<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer;
use Rector\Defluent\NodeAnalyzer\SameClassMethodCallAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.0/Deprecation-89938-DeprecatedLanguageModeInTypo3QuerySettings.html
 */
final class RemoveLanguageModeMethodsFromTypo3QuerySettingsRector extends AbstractRector
{
    /**
     * @var FluentChainMethodCallNodeAnalyzer
     */
    private $fluentChainMethodCallNodeAnalyzer;

    /**
     * @var SameClassMethodCallAnalyzer
     */
    private $sameClassMethodCallAnalyzer;

    public function __construct(
        FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer,
        SameClassMethodCallAnalyzer $sameClassMethodCallAnalyzer
    ) {
        $this->fluentChainMethodCallNodeAnalyzer = $fluentChainMethodCallNodeAnalyzer;
        $this->sameClassMethodCallAnalyzer = $sameClassMethodCallAnalyzer;
    }

    /**
     * @return array<class-string<Node>>
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(Typo3QuerySettings::class)
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['setLanguageMode', 'getLanguageMode'])) {
            return null;
        }

        return $this->removeMethodCall($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove language mode methods from class Typo3QuerySettings', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
$querySettings = new Typo3QuerySettings();
$querySettings->setLanguageUid(0)->setLanguageMode()->getLanguageMode();
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
$querySettings = new Typo3QuerySettings();
$querySettings->setLanguageUid(0);
CODE_SAMPLE
            ),
        ]);
    }

    private function removeMethodCall(MethodCall $node): ?Node
    {
        try {
            // If it is the only method call, we can safely delete the node here.
            $this->removeNode($node);

            return $node;
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            $chainMethodCalls = $this->fluentChainMethodCallNodeAnalyzer->collectAllMethodCallsInChain($node);

            if (! $this->sameClassMethodCallAnalyzer->haveSingleClass($chainMethodCalls)) {
                return null;
            }

            foreach ($chainMethodCalls as $chainMethodCall) {
                if ($this->isNames($chainMethodCall->name, ['setLanguageMode', 'getLanguageMode'])) {
                    continue;
                }

                $node->var = new MethodCall($chainMethodCall->var, $chainMethodCall->name, $chainMethodCall->args);
            }

            return $node->var;
        }
    }
}
