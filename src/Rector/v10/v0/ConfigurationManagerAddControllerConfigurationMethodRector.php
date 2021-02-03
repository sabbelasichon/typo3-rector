<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Configuration\AbstractConfigurationManager;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Breaking-88496-MethodGetSwitchableControllerActionsHasBeenRemoved.html
 */
final class ConfigurationManagerAddControllerConfigurationMethodRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, AbstractConfigurationManager::class)) {
            return null;
        }
        $this->addMethodGetControllerConfiguration($node);
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add additional method getControllerConfiguration for AbstractConfigurationManager',
            [
                new CodeSample(<<<'CODE_SAMPLE'
final class MyExtbaseConfigurationManager extends AbstractConfigurationManager
{
    protected function getSwitchableControllerActions($extensionName, $pluginName)
    {
        $switchableControllerActions = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['modules'][$pluginName]['controllers'] ?? false;
        if ( ! is_array($switchableControllerActions)) {
            $switchableControllerActions = [];
        }

        return $switchableControllerActions;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class MyExtbaseConfigurationManager extends AbstractConfigurationManager
{
    protected function getSwitchableControllerActions($extensionName, $pluginName)
    {
        $switchableControllerActions = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['modules'][$pluginName]['controllers'] ?? false;
        if ( ! is_array($switchableControllerActions)) {
            $switchableControllerActions = [];
        }

        return $switchableControllerActions;
    }

    protected function getControllerConfiguration($extensionName, $pluginName): array
    {
        return $this->getSwitchableControllerActions($extensionName, $pluginName);
    }
}
CODE_SAMPLE
),
            ]
        );
    }

    private function addMethodGetControllerConfiguration(Class_ $node): void
    {
        $methodBuilder = $this->builderFactory->method('getControllerConfiguration');
        $methodBuilder->makeProtected();
        $methodBuilder->addParams(
            [$this->builderFactory->param('extensionName')->getNode(), $this->builderFactory->param(
                'pluginName'
            )->getNode()]
        );
        $newMethod = $methodBuilder->getNode();
        $newMethod->returnType = new Identifier('array');
        $newMethod->stmts[] = new Return_($this->nodeFactory->createMethodCall(
            'this',
            'getSwitchableControllerActions',
            [new Variable('extensionName'), new Variable('pluginName')]
        ));
        $node->stmts[] = new Nop();
        $node->stmts[] = $newMethod;
    }
}
