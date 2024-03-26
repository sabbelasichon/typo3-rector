<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Rendering;

use TYPO3Fluid\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\ErrorHandler\ErrorHandlerInterface;
use TYPO3Fluid\Fluid\Core\ErrorHandler\StandardErrorHandler;
use TYPO3Fluid\Fluid\Core\Parser\Configuration;
use TYPO3Fluid\Fluid\Core\Parser\Interceptor\Escape;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\CastingExpressionNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\MathExpressionNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\TernaryExpressionNode;
use TYPO3Fluid\Fluid\Core\Parser\TemplateParser;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessor\EscapingModifierTemplateProcessor;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessor\NamespaceDetectionTemplateProcessor;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessor\PassthroughSourceModifierTemplateProcessor;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessorInterface;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInvoker;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperResolver;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer;
use TYPO3Fluid\Fluid\View\TemplatePaths;

/**
 * The rendering context that contains useful information during rendering time of a Fluid template
 */
class RenderingContext implements RenderingContextInterface
{
    /**
     * @var ErrorHandlerInterface
     */
    protected $errorHandler;

    /**
     * Template Variable Container. Contains all variables available through object accessors in the template
     *
     * @var VariableProviderInterface
     */
    protected $variableProvider;

    /**
     * ViewHelper Variable Container
     *
     * @var ViewHelperVariableContainer
     */
    protected $viewHelperVariableContainer;

    /**
     * @var ViewHelperResolver
     */
    protected $viewHelperResolver;

    /**
     * @var ViewHelperInvoker
     */
    protected $viewHelperInvoker;

    /**
     * @var TemplatePaths
     */
    protected $templatePaths;

    /**
     * @var string
     */
    protected $controllerName = '';

    /**
     * @var string
     */
    protected $controllerAction;

    /**
     * @var TemplateParser
     */
    protected $templateParser;

    /**
     * @var TemplateCompiler
     */
    protected $templateCompiler;

    /**
     * @var FluidCacheInterface
     */
    protected $cache;

    /**
     * @var TemplateProcessorInterface[]
     */
    protected $templateProcessors = [];

    /**
     * List of class names implementing ExpressionNodeInterface
     * which will be consulted when an expression does not match
     * any built-in parser expression types.
     *
     * @var array
     */
    protected $expressionNodeTypes = [
        CastingExpressionNode::class,
        MathExpressionNode::class,
        TernaryExpressionNode::class,
    ];

    /**
     * Constructor
     *
     * Constructing a RenderingContext should result in an object containing instances
     * in all properties of the object. Subclassing RenderingContext allows changing the
     * types of instances that are created.
     *
     * Setters are used to fill the object instances. Some setters will call the
     * setRenderingContext() method (convention name) to provide the instance that is
     * created with an instance of the "parent" RenderingContext.
     */
    public function __construct()
    {
        $this->setTemplateParser(new TemplateParser());
        $this->setTemplateCompiler(new TemplateCompiler());
        $this->setTemplatePaths(new TemplatePaths());
        $this->setTemplateProcessors(
            [
                new EscapingModifierTemplateProcessor(),
                new PassthroughSourceModifierTemplateProcessor(),
                new NamespaceDetectionTemplateProcessor()
            ]
        );
        $this->setViewHelperResolver(new ViewHelperResolver());
        $this->setViewHelperInvoker(new ViewHelperInvoker());
        $this->setViewHelperVariableContainer(new ViewHelperVariableContainer());
        $this->setVariableProvider(new StandardVariableProvider());
    }

    /**
     * @return ErrorHandlerInterface
     */
    public function getErrorHandler()
    {
        return isset($this->errorHandler) ? $this->errorHandler : new StandardErrorHandler();
    }

    /**
     * @param ErrorHandlerInterface $errorHandler
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * Injects the template variable container containing all variables available through ObjectAccessors
     * in the template
     *
     * @param VariableProviderInterface $variableProvider The template variable container to set
     */
    public function setVariableProvider(VariableProviderInterface $variableProvider)
    {
        $this->variableProvider = $variableProvider;
    }

    /**
     * Get the template variable container
     *
     * @return VariableProviderInterface The Template Variable Container
     */
    public function getVariableProvider()
    {
        return $this->variableProvider;
    }

    /**
     * @return ViewHelperResolver
     */
    public function getViewHelperResolver()
    {
        return $this->viewHelperResolver;
    }

    /**
     * @param ViewHelperResolver $viewHelperResolver
     */
    public function setViewHelperResolver(ViewHelperResolver $viewHelperResolver)
    {
        $this->viewHelperResolver = $viewHelperResolver;
    }

    /**
     * @return ViewHelperInvoker
     */
    public function getViewHelperInvoker()
    {
        return $this->viewHelperInvoker;
    }

    /**
     * @param ViewHelperInvoker $viewHelperInvoker
     */
    public function setViewHelperInvoker(ViewHelperInvoker $viewHelperInvoker)
    {
        $this->viewHelperInvoker = $viewHelperInvoker;
    }

    /**
     * @return TemplatePaths
     */
    public function getTemplatePaths()
    {
        return $this->templatePaths;
    }

    /**
     * @param TemplatePaths $templatePaths
     */
    public function setTemplatePaths(TemplatePaths $templatePaths)
    {
        $this->templatePaths = $templatePaths;
    }

    /**
     * Set the ViewHelperVariableContainer
     *
     * @param ViewHelperVariableContainer $viewHelperVariableContainer
     */
    public function setViewHelperVariableContainer(ViewHelperVariableContainer $viewHelperVariableContainer)
    {
        $this->viewHelperVariableContainer = $viewHelperVariableContainer;
    }

    /**
     * Get the ViewHelperVariableContainer
     *
     * @return ViewHelperVariableContainer
     */
    public function getViewHelperVariableContainer()
    {
        return $this->viewHelperVariableContainer;
    }

    /**
     * Inject the Template Parser
     *
     * @param TemplateParser $templateParser The template parser
     */
    public function setTemplateParser(TemplateParser $templateParser)
    {
        $this->templateParser = $templateParser;
        $this->templateParser->setRenderingContext($this);
    }

    /**
     * @return TemplateParser
     */
    public function getTemplateParser()
    {
        return $this->templateParser;
    }

    /**
     * @param TemplateCompiler $templateCompiler
     */
    public function setTemplateCompiler(TemplateCompiler $templateCompiler)
    {
        $this->templateCompiler = $templateCompiler;
        $this->templateCompiler->setRenderingContext($this);
    }

    /**
     * @return TemplateCompiler
     */
    public function getTemplateCompiler()
    {
        return $this->templateCompiler;
    }

    /**
     * Delegation: Set the cache used by this View's compiler
     *
     * @param FluidCacheInterface $cache
     */
    public function setCache(FluidCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return FluidCacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return bool
     */
    public function isCacheEnabled()
    {
        return $this->cache instanceof FluidCacheInterface;
    }

    /**
     * Delegation: Set TemplateProcessor instances in the parser
     * through a public API.
     *
     * @param TemplateProcessorInterface[] $templateProcessors
     */
    public function setTemplateProcessors(array $templateProcessors)
    {
        $this->templateProcessors = $templateProcessors;
        foreach ($this->templateProcessors as $templateProcessor) {
            $templateProcessor->setRenderingContext($this);
        }
    }

    /**
     * @return TemplateProcessorInterface[]
     */
    public function getTemplateProcessors()
    {
        return $this->templateProcessors;
    }

    /**
     * @return array<string>
     */
    public function getExpressionNodeTypes()
    {
        return $this->expressionNodeTypes;
    }

    /**
     * @param array $expressionNodeTypes
     */
    public function setExpressionNodeTypes(array $expressionNodeTypes)
    {
        $this->expressionNodeTypes = $expressionNodeTypes;
    }

    /**
     * Build parser configuration
     *
     * @return Configuration
     */
    public function buildParserConfiguration()
    {
        $parserConfiguration = new Configuration();
        $escapeInterceptor = new Escape();
        $parserConfiguration->addEscapingInterceptor($escapeInterceptor);
        return $parserConfiguration;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @param string $controllerName
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }

    /**
     * @return string
     */
    public function getControllerAction()
    {
        return $this->controllerAction;
    }

    /**
     * @param string $action
     */
    public function setControllerAction($action)
    {
        $this->controllerAction = $action;
    }
}
