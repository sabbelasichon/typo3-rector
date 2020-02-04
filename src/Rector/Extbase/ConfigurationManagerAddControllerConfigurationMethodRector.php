<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Configuration\AbstractConfigurationManager;

final class ConfigurationManagerAddControllerConfigurationMethodRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @var Node|Class_
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, AbstractConfigurationManager::class)) {
            return null;
        }

        $this->addMethodGetControllerConfiguration($node);

        return null;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Add additional method getControllerConfiguration for AbstractConfigurationManager',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
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
                    ,
                    <<<'CODE_SAMPLE'
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

    private function addMethodGetControllerConfiguration(Node $node): void
    {
        $methodBuilder = $this->builderFactory->method('getControllerConfiguration');
        $methodBuilder->makeProtected();
        $methodBuilder->addParams([
            $this->builderFactory->param('extensionName')->getNode(),
            $this->builderFactory->param('pluginName')->getNode(),
        ]);

        $newMethod = $methodBuilder->getNode();
        $newMethod->returnType = new Identifier('array');
        $newMethod->stmts[] = new Return_($this->createMethodCall('this', 'getSwitchableControllerActions', [new Variable('extensionName'), new Variable('pluginName')]));

        $node->stmts[] = new Nop();
        $node->stmts[] = $newMethod;
    }
}
