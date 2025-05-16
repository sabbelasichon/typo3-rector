<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.3/Deprecation-101559-ExtbaseUsesExtcoreViewInterface.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v3\UseTYPO3CoreViewInterfaceInExtbaseRector\UseTYPO3CoreViewInterfaceInExtbaseRectorTest
 */
final class UseTYPO3CoreViewInterfaceInExtbaseRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, array{newMethod: string, on?: string, needsViewRef?: bool, setAttribute?: bool, attributeClass?: string}>
     */
    private const DEPRECATED_METHODS_MAP = [
        'setTemplate' => [
            'newMethod' => 'setControllerAction',
        ],
        'initializeRenderingContext' => [
            'newMethod' => 'setView',
            'on' => 'getViewHelperVariableContainer',
            'needsViewRef' => true,
        ],
        'setCache' => [
            'newMethod' => 'setCache',
        ],
        'getTemplatePaths' => [
            'newMethod' => 'getTemplatePaths',
        ],
        'getViewHelperResolver' => [
            'newMethod' => 'getViewHelperResolver',
        ],
        'setTemplatePathAndFilename' => [
            'newMethod' => 'setTemplatePathAndFilename',
            'on' => 'getTemplatePaths',
        ],
        'setTemplateRootPaths' => [
            'newMethod' => 'setTemplateRootPaths',
            'on' => 'getTemplatePaths',
        ],
        'getTemplateRootPaths' => [
            'newMethod' => 'getTemplateRootPaths',
            'on' => 'getTemplatePaths',
        ],
        'setPartialRootPaths' => [
            'newMethod' => 'setPartialRootPaths',
            'on' => 'getTemplatePaths',
        ],
        'getPartialRootPaths' => [
            'newMethod' => 'getPartialRootPaths',
            'on' => 'getTemplatePaths',
        ],
        'getLayoutRootPaths' => [
            'newMethod' => 'getLayoutRootPaths',
            'on' => 'getTemplatePaths',
        ],
        'setLayoutRootPaths' => [
            'newMethod' => 'setLayoutRootPaths',
            'on' => 'getTemplatePaths',
        ],
        'setLayoutPathAndFilename' => [
            'newMethod' => 'setLayoutPathAndFilename',
            'on' => 'getTemplatePaths',
        ],
        'setRequest' => [
            'newMethod' => 'setAttribute',
            'setAttribute' => true,
            'attributeClass' => 'Psr\Http\Message\ServerRequestInterface',
        ],
        'setTemplateSource' => [
            'newMethod' => 'setTemplateSource',
            'on' => 'getTemplatePaths',
        ],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use `\\TYPO3\\CMS\\Core\\View\\ViewInterface` in Extbase and call `$view->getRenderingContext()` to perform operations instead',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction()
    {
        // setTemplate
        $this->view->setTemplate('MyTemplate');

        // initializeRenderingContext
        $this->view->initializeRenderingContext();

        // setCache
        $cache = new SimpleFileCache();
        $this->view->setCache($cache);

        // getTemplatePaths
        $templatePaths = $this->view->getTemplatePaths();

        // getViewHelperResolver
        $viewHelperResolver = $this->view->getViewHelperResolver();

        // setTemplatePathAndFilename
        $this->view->setTemplatePathAndFilename('path/to/template.html');

        // setTemplateRootPaths
        $this->view->setTemplateRootPaths(['path/to/templates/']);

        // getTemplateRootPaths
        $rootPaths = $this->view->getTemplateRootPaths();

        // setPartialRootPaths
        $this->view->setPartialRootPaths(['path/to/partials/']);

        // getPartialRootPaths
        $partialPaths = $this->view->getPartialRootPaths();

        // getLayoutRootPaths
        $layoutPaths = $this->view->getLayoutRootPaths();

        // setLayoutRootPaths
        $this->view->setLayoutRootPaths(['path/to/layouts/']);

        // setLayoutPathAndFilename
        $this->view->setLayoutPathAndFilename('path/to/layout.html');

        // setRequest
        $this->view->setRequest($this->request);

        // setTemplateSource
        $this->view->setTemplateSource('<f:render section="Main" />');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction()
    {
        // setTemplate
        $this->view->getRenderingContext()->setControllerAction('MyTemplate');

        // initializeRenderingContext
        $this->view->getRenderingContext()->getViewHelperVariableContainer()->setView($this->view);

        // setCache
        $cache = new SimpleFileCache();
        $this->view->getRenderingContext()->setCache($cache);

        // getTemplatePaths
        $templatePaths = $this->view->getRenderingContext()->getTemplatePaths();

        // getViewHelperResolver
        $viewHelperResolver = $this->view->getRenderingContext()->getViewHelperResolver();

        // setTemplatePathAndFilename
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplatePathAndFilename('path/to/template.html');

        // setTemplateRootPaths
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateRootPaths(['path/to/templates/']);

        // getTemplateRootPaths
        $rootPaths = $this->view->getRenderingContext()->getTemplatePaths()->getTemplateRootPaths();

        // setPartialRootPaths
        $this->view->getRenderingContext()->getTemplatePaths()->setPartialRootPaths(['path/to/partials/']);

        // getPartialRootPaths
        $partialPaths = $this->view->getRenderingContext()->getTemplatePaths()->getPartialRootPaths();

        // getLayoutRootPaths
        $layoutPaths = $this->view->getRenderingContext()->getTemplatePaths()->getLayoutRootPaths();

        // setLayoutRootPaths
        $this->view->getRenderingContext()->getTemplatePaths()->setLayoutRootPaths(['path/to/layouts/']);

        // setLayoutPathAndFilename
        $this->view->getRenderingContext()->getTemplatePaths()->setLayoutPathAndFilename('path/to/layout.html');

        // setRequest
        $this->view->getRenderingContext()->setAttribute(ServerRequestInterface::class, $this->request);

        // setTemplateSource
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateSource('<f:render section="Main" />');
    }
}
CODE_SAMPLE
                ),

            ]
        );
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null || ! isset(self::DEPRECATED_METHODS_MAP[$methodName])) {
            return null;
        }

        $methodConfig = self::DEPRECATED_METHODS_MAP[$methodName];
        $originalArgs = $node->getArgs();

        // $this->view->getRenderingContext() or $variable->getRenderingContext()
        $renderingContextCall = $this->nodeFactory->createMethodCall($node->var, 'getRenderingContext');

        $finalCallOn = $renderingContextCall;
        if (isset($methodConfig['on'])) {
            $finalCallOn = $this->nodeFactory->createMethodCall($renderingContextCall, $methodConfig['on']);
        }

        $newArgs = $originalArgs;
        if (isset($methodConfig['needsViewRef'])) {
            $newArgs = [new Arg($node->var)];
        } elseif (isset($methodConfig['setAttribute'])) {
            $newArgs = [
                new Arg($this->nodeFactory->createClassConstReference($methodConfig['attributeClass'])),
                $originalArgs[0],
            ];
        }

        return $this->nodeFactory->createMethodCall($finalCallOn, $methodConfig['newMethod'], $newArgs);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        // Ensure the call is on $this->view or a variable of the correct type
        if ($methodCall->var instanceof PropertyFetch) {
            /** @var PropertyFetch $propertyFetch */
            $propertyFetch = $methodCall->var;
            // Not $this->view, check if it's another variable of the correct type
            if ((! $this->isName($propertyFetch->var, 'this') || ! $this->isName(
                $propertyFetch->name,
                'view'
            )) && (! $this->isObjectType(
                $methodCall->var,
                new ObjectType('TYPO3\CMS\Extbase\Mvc\View\ViewInterface')
            ) && ! $this->isObjectType(
                $methodCall->var,
                new ObjectType('TYPO3\CMS\Core\View\ViewInterface')
            ) && ! $this->isObjectType($methodCall->var, new ObjectType('TYPO3Fluid\Fluid\View\ViewInterface')))) {
                return true;
            }
        } elseif (! $this->isObjectType($methodCall->var, new ObjectType('TYPO3\CMS\Extbase\Mvc\View\ViewInterface'))
            && ! $this->isObjectType($methodCall->var, new ObjectType('TYPO3\CMS\Core\View\ViewInterface'))
            && ! $this->isObjectType($methodCall->var, new ObjectType('TYPO3Fluid\Fluid\View\ViewInterface'))) {
            // Not a property fetch, check if it's a variable of the correct type
            return true;
        }

        $methodName = $this->getName($methodCall->name);
        if ($methodName === null) {
            return true;
        }

        // Skip setFormat and hasTemplate as it requires more complex logic
        if ($methodName === 'setFormat' || $methodName === 'hasTemplate') {
            return true;
        }

        return ! isset(self::DEPRECATED_METHODS_MAP[$methodName]);
    }
}
