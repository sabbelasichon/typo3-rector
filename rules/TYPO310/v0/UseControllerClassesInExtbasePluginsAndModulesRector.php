<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Deprecation-87550-UseControllerClassesWhenRegisteringPluginsmodules.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector\UseControllerClassesInExtbasePluginsAndModulesRectorTest
 */
final class UseControllerClassesInExtbasePluginsAndModulesRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    /**
     * @readonly
     */
    private StaticTypeMapper $staticTypeMapper;

    private bool $hasChanges = false;

    public function __construct(
        FileInfoFactory $fileInfoFactory,
        ValueResolver $valueResolver,
        StaticTypeMapper $staticTypeMapper
    ) {
        $this->fileInfoFactory = $fileInfoFactory;
        $this->valueResolver = $valueResolver;
        $this->staticTypeMapper = $staticTypeMapper;
    }

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
        if (! $this->isNames($node->name, ['configurePlugin', 'registerModule'])) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Extbase\Utility\ExtensionUtility')
        )) {
            return null;
        }

        $extensionNameArgumentValue = $node->args[0]->value;

        $extensionName = $this->valueResolver->getValue($extensionNameArgumentValue);

        $fileInfo = $this->fileInfoFactory->createFileInfoFromPath($this->file->getFilePath());

        if ($extensionNameArgumentValue instanceof Concat
            && $this->isPotentiallyUndefinedExtensionKeyVariable($extensionNameArgumentValue)
        ) {
            $extensionName = $this->valueResolver->getValue($extensionNameArgumentValue->left) . basename(
                $fileInfo->getRelativePath()
            );
        }

        if (! is_string($extensionName)) {
            return null;
        }

        $delimiterPosition = strrpos($extensionName, '.');
        if ($delimiterPosition === false) {
            return null;
        }

        $vendorName = $this->prepareVendorName($extensionName, $delimiterPosition);
        $extensionName = StringUtility::prepareExtensionName($extensionName, $delimiterPosition);
        if ($extensionName === '') {
            return null;
        }

        $node->args[0] = $this->nodeFactory->createArg($extensionName);

        if ($this->isName($node->name, 'configurePlugin')) {
            $this->refactorConfigurePluginMethod($node, $vendorName, $extensionName);

            return $this->hasChanges ? $node : null;
        }

        $this->refactorRegisterPluginMethod($node, $vendorName, $extensionName);

        return $this->hasChanges ? $node : null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use controller classes when registering extbase plugins/modules', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
ExtensionUtility::configurePlugin(
    'TYPO3.CMS.Form',
    'Formframework',
    ['FormFrontend' => 'render, perform'],
    ['FormFrontend' => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;ExtensionUtility::configurePlugin(
    'Form',
    'Formframework',
    [\TYPO3\CMS\Form\Controller\FormFrontendController::class => 'render, perform'],
    [\TYPO3\CMS\Form\Controller\FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
CODE_SAMPLE
            ),
        ]);
    }

    private function refactorConfigurePluginMethod(
        StaticCall $staticCall,
        string $vendorName,
        string $extensionName
    ): void {
        if (isset($staticCall->args[2]) && $staticCall->args[2]->value instanceof Array_) {
            $this->createNewControllerActionsArray($staticCall->args[2]->value, $vendorName, $extensionName);
        }

        if (isset($staticCall->args[3]) && $staticCall->args[3]->value instanceof Array_) {
            $this->createNewControllerActionsArray($staticCall->args[3]->value, $vendorName, $extensionName);
        }
    }

    private function createNewControllerActionsArray(
        Array_ $controllerActions,
        string $vendorName,
        string $extensionName
    ): void {
        foreach ($controllerActions->items as $controllerAction) {
            if (! $controllerAction instanceof ArrayItem) {
                continue;
            }

            if (! $controllerAction->key instanceof Expr) {
                continue;
            }

            $controllerClassName = $this->valueResolver->getValue($controllerAction->key);

            if ($controllerClassName === null) {
                continue;
            }

            $controllerClassNameType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($controllerAction->key);

            if ($controllerClassNameType->getConstantStrings() !== []) {
                // Already transformed
                continue;
            }

            $controllerAction->key = $this->nodeFactory->createClassConstReference(
                $this->getControllerClassName($vendorName, $extensionName, $controllerClassName)
            );
            $this->hasChanges = true;
        }
    }

    private function getControllerClassName(
        string $vendor,
        string $extensionKey,
        string $controllerAlias
    ): string {
        $objectName = str_replace(
            ['@extension', '@subpackage', '@controller', '@vendor', '\\\\'],
            [$extensionKey, '', $controllerAlias, $vendor, '\\'],
            '@vendor\@extension\@subpackage\Controller\@controllerController'
        );

        return trim($objectName, '\\');
    }

    private function refactorRegisterPluginMethod(
        StaticCall $staticCall,
        string $vendorName,
        string $extensionName
    ): void {
        if (isset($staticCall->args[4]) && $staticCall->args[4]->value instanceof Array_) {
            $this->createNewControllerActionsArray($staticCall->args[4]->value, $vendorName, $extensionName);
        }
    }

    private function isPotentiallyUndefinedExtensionKeyVariable(Concat $extensionNameArgumentValue): bool
    {
        if (! $extensionNameArgumentValue->right instanceof Variable) {
            return false;
        }

        return $this->valueResolver->getValue($extensionNameArgumentValue->right) === null;
    }

    private function prepareVendorName(string $extensionName, int $delimiterPosition): string
    {
        return str_replace('.', '\\', substr($extensionName, 0, $delimiterPosition));
    }
}
