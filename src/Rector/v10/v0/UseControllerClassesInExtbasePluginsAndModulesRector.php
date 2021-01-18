<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-87550-UseControllerClassesWhenRegisteringPluginsmodules.html
 */
final class UseControllerClassesInExtbasePluginsAndModulesRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ExtensionUtility::class)) {
            return null;
        }

        if (! $this->isNames($node->name, ['configurePlugin', 'registerModule'])) {
            return null;
        }

        $extensionNameArgumentValue = $node->args[0]->value;

        $extensionName = $this->getValue($extensionNameArgumentValue);

        if ($extensionNameArgumentValue instanceof Concat && $this->isPotentiallyUndefinedExtensionKeyVariable(
            $extensionNameArgumentValue
        )) {
            /** @var SmartFileInfo $fileInfo */
            $fileInfo = $node->getAttribute(AttributeKey::FILE_INFO);

            $extensionName = $this->getValue($extensionNameArgumentValue->left) . basename(
                $fileInfo->getRelativeDirectoryPath()
            );
        }

        if (! is_string($extensionName)) {
            return null;
        }

        $delimiterPosition = strrpos($extensionName, '.');
        if (false === $delimiterPosition) {
            return null;
        }

        $vendorName = $this->prepareVendorName($extensionName, $delimiterPosition);
        $extensionName = $this->prepareExtensionName($extensionName, $delimiterPosition);

        $node->args[0] = $this->createArg($extensionName);

        if ($this->isName($node->name, 'configurePlugin')) {
            $this->refactorConfigurePluginMethod($node, $vendorName, $extensionName);

            return null;
        }

        $this->refactorRegisterPluginMethod($node, $vendorName, $extensionName);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use controller classes when registering extbase plugins/modules', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
ExtensionUtility::configurePlugin(
    'TYPO3.CMS.Form',
    'Formframework',
    ['FormFrontend' => 'render, perform'],
    ['FormFrontend' => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
PHP
                , <<<'PHP'
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;ExtensionUtility::configurePlugin(
    'Form',
    'Formframework',
    [\TYPO3\CMS\Form\Controller\FormFrontendController::class => 'render, perform'],
    [\TYPO3\CMS\Form\Controller\FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
PHP
            ),
        ]);
    }

    private function getControllerClassName(
        string $vendor,
        string $extensionKey,
        string $subPackageKey,
        string $controllerAlias
    ): string {
        $objectName = str_replace(
            ['@extension', '@subpackage', '@controller', '@vendor', '\\\\'],
            [$extensionKey, $subPackageKey, $controllerAlias, $vendor, '\\'],
            '@vendor\@extension\@subpackage\Controller\@controllerController'
        );

        return trim($objectName, '\\');
    }

    private function createNewControllerActionsArray(
        Array_ $controllerActions,
        string $vendorName,
        string $extensionName
    ): void {
        foreach ($controllerActions->items as $controllerActions) {
            if (! $controllerActions instanceof ArrayItem) {
                continue;
            }

            if (null === $controllerActions->key) {
                continue;
            }

            $controllerClassName = $this->getValue($controllerActions->key);

            if (null === $controllerClassName) {
                continue;
            }

            // If already transformed
            if (class_exists($controllerClassName)) {
                continue;
            }

            $controllerActions->key = $this->createClassConstReference(
                    $this->getControllerClassName($vendorName, $extensionName, '', $controllerClassName)
                );
        }
    }

    private function refactorConfigurePluginMethod(StaticCall $node, string $vendorName, string $extensionName): void
    {
        if (isset($node->args[2]) && $node->args[2]->value instanceof Array_) {
            $this->createNewControllerActionsArray($node->args[2]->value, $vendorName, $extensionName);
        }

        if (isset($node->args[3]) && $node->args[3]->value instanceof Array_) {
            $this->createNewControllerActionsArray($node->args[3]->value, $vendorName, $extensionName);
        }
    }

    private function refactorRegisterPluginMethod(StaticCall $node, string $vendorName, string $extensionName): void
    {
        if (isset($node->args[4]) && $node->args[4]->value instanceof Array_) {
            $this->createNewControllerActionsArray($node->args[4]->value, $vendorName, $extensionName);
        }
    }

    private function isPotentiallyUndefinedExtensionKeyVariable(Concat $extensionNameArgumentValue): bool
    {
        if (! $extensionNameArgumentValue->right instanceof Variable) {
            return false;
        }

        if (null !== $this->getValue($extensionNameArgumentValue->right)) {
            return false;
        }
        return $this->isNames($extensionNameArgumentValue->right, ['_EXTKEY', 'extensionKey']);
    }

    private function prepareExtensionName(string $extensionName, int $delimiterPosition): string
    {
        $extensionName = substr($extensionName, $delimiterPosition + 1);
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $extensionName)));
    }

    private function prepareVendorName(string $extensionName, int $delimiterPosition): string
    {
        return str_replace('.', '\\', substr($extensionName, 0, $delimiterPosition));
    }
}
