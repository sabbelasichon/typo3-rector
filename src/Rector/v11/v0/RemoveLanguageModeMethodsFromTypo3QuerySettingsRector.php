<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\FluentChainMethodCallNodeAnalyzer;
use Ssch\TYPO3Rector\NodeAnalyzer\SameClassMethodCallAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-89938-DeprecatedLanguageModeInTypo3QuerySettings.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\RemoveLanguageModeMethodsFromTypo3QuerySettingsRector\RemoveLanguageModeMethodsFromTypo3QuerySettingsRectorTest
 */
final class RemoveLanguageModeMethodsFromTypo3QuerySettingsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer;

    /**
     * @readonly
     */
    private SameClassMethodCallAnalyzer $sameClassMethodCallAnalyzer;

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
            new ObjectType('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\QuerySettingsInterface')
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
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

$querySettings = new Typo3QuerySettings();
$querySettings->setLanguageUid(0)->setLanguageMode()->getLanguageMode();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

$querySettings = new Typo3QuerySettings();
$querySettings->setLanguageUid(0);
CODE_SAMPLE
            ),
        ]);
    }

    private function removeMethodCall(MethodCall $methodCall): ?Node
    {
        try {
            // If it is the only method call, we can safely delete the node here.
            $this->removeNode($methodCall);

            return $methodCall;
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            $chainMethodCalls = $this->fluentChainMethodCallNodeAnalyzer->collectAllMethodCallsInChain($methodCall);

            if (! $this->sameClassMethodCallAnalyzer->haveSingleClass($chainMethodCalls)) {
                return null;
            }

            foreach ($chainMethodCalls as $chainMethodCall) {
                if ($this->isNames($chainMethodCall->name, ['setLanguageMode', 'getLanguageMode'])) {
                    continue;
                }

                $methodCall->var = new MethodCall(
                    $chainMethodCall->var,
                    $chainMethodCall->name,
                    $chainMethodCall->args
                );
            }

            return $methodCall->var;
        }
    }
}
