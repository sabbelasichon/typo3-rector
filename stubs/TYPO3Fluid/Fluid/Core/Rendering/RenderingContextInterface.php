<?php

namespace TYPO3Fluid\Fluid\Core\Rendering;

use TYPO3Fluid\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\ErrorHandler\ErrorHandlerInterface;
use TYPO3Fluid\Fluid\Core\Parser\Configuration;
use TYPO3Fluid\Fluid\Core\Parser\TemplateParser;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessorInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInvoker;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperResolver;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer;
use TYPO3Fluid\Fluid\View\TemplatePaths;

if (interface_exists('TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface')) {
    return;
}

interface RenderingContextInterface
{
    /**
     * @return ErrorHandlerInterface
     */
    public function getErrorHandler();

    /**
     * @param ErrorHandlerInterface $errorHandler
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler);

    /**
     * Injects the template variable container containing all variables available through ObjectAccessors
     * in the template
     *
     * @param VariableProviderInterface $variableProvider The template variable container to set
     */
    public function setVariableProvider(VariableProviderInterface $variableProvider);

    /**
     * @param ViewHelperVariableContainer $viewHelperVariableContainer
     */
    public function setViewHelperVariableContainer(ViewHelperVariableContainer $viewHelperVariableContainer);

    /**
     * Get the template variable container
     *
     * @return VariableProviderInterface The Template Variable Container
     */
    public function getVariableProvider();

    /**
     * Get the ViewHelperVariableContainer
     *
     * @return ViewHelperVariableContainer
     */
    public function getViewHelperVariableContainer();

    /**
     * @return ViewHelperResolver
     */
    public function getViewHelperResolver();

    /**
     * @param ViewHelperResolver $viewHelperResolver
     */
    public function setViewHelperResolver(ViewHelperResolver $viewHelperResolver);

    /**
     * @return ViewHelperInvoker
     */
    public function getViewHelperInvoker();

    /**
     * @param ViewHelperInvoker $viewHelperInvoker
     */
    public function setViewHelperInvoker(ViewHelperInvoker $viewHelperInvoker);

    /**
     * Inject the Template Parser
     *
     * @param TemplateParser $templateParser The template parser
     */
    public function setTemplateParser(TemplateParser $templateParser);

    /**
     * @return TemplateParser
     */
    public function getTemplateParser();

    /**
     * @param TemplateCompiler $templateCompiler
     */
    public function setTemplateCompiler(TemplateCompiler $templateCompiler);

    /**
     * @return TemplateCompiler
     */
    public function getTemplateCompiler();

    /**
     * @return TemplatePaths
     */
    public function getTemplatePaths();

    /**
     * @param TemplatePaths $templatePaths
     */
    public function setTemplatePaths(TemplatePaths $templatePaths);

    /**
     * Delegation: Set the cache used by this View's compiler
     *
     * @param FluidCacheInterface $cache
     */
    public function setCache(FluidCacheInterface $cache);

    /**
     * @return FluidCacheInterface
     */
    public function getCache();

    /**
     * @return bool
     */
    public function isCacheEnabled();

    /**
     * Delegation: Set TemplateProcessor instances in the parser
     * through a public API.
     *
     * @param TemplateProcessorInterface[] $templateProcessors
     */
    public function setTemplateProcessors(array $templateProcessors);

    /**
     * @return TemplateProcessorInterface[]
     */
    public function getTemplateProcessors();

    /**
     * @return array
     */
    public function getExpressionNodeTypes();

    /**
     * @param array $expressionNodeTypes
     */
    public function setExpressionNodeTypes(array $expressionNodeTypes);

    /**
     * Build parser configuration
     *
     * @return Configuration
     */
    public function buildParserConfiguration();

    /**
     * @return string
     */
    public function getControllerName();

    /**
     * @param string $controllerName
     */
    public function setControllerName($controllerName);

    /**
     * @return string
     */
    public function getControllerAction();

    /**
     * @param string $action
     */
    public function setControllerAction($action);

    /**
     * Add an object to this instance.
     *
     * This method allows you to attach arbitrary objects to the
     * rendering context to be used later e.g. in ViewHelpers.
     *
     * A typical use case is to attach a ServerRequestInterface here.
     *
     * @template T of object
     * @param class-string<T> $className
     * @param T $value
     */
    public function setAttribute(string $className, object $value): void;

    /**
     * Return true if an attribute object of that type exists.
     *
     * @template T of object
     * @param class-string<T> $className
     */
    public function hasAttribute(string $className): bool;

    /**
     * Retrieve a single attribute instance.
     *
     * @template T of object
     * @param class-string<T> $className
     * @return T
     */
    public function getAttribute(string $className): object;
}
