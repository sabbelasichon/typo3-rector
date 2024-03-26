<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\View;

use TYPO3Fluid\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3Fluid\Fluid\Core\Parser\ParsedTemplateInterface;
use TYPO3Fluid\Fluid\Core\Parser\PassthroughSourceException;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperResolver;
use TYPO3Fluid\Fluid\View\Exception\InvalidSectionException;
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;
use TYPO3Fluid\Fluid\ViewHelpers\SectionViewHelper;

/**
 * Abstract Fluid Template View.
 *
 * Contains the fundamental methods which any Fluid based template view needs.
 */
abstract class AbstractTemplateView extends AbstractView implements TemplateAwareViewInterface
{
    /**
     * Constants defining possible rendering types
     */
    public const RENDERING_TEMPLATE = 1;
    public const RENDERING_PARTIAL = 2;
    public const RENDERING_LAYOUT = 3;

    /**
     * The initial rendering context for this template view.
     * Due to the rendering stack, another rendering context might be active
     * at certain points while rendering the template.
     *
     * @var RenderingContextInterface
     */
    protected $baseRenderingContext;

    /**
     * Stack containing the current rendering type, the current rendering context, and the current parsed template
     * Do not manipulate directly, instead use the methods"getCurrent*()", "startRendering(...)" and "stopRendering()"
     *
     * @var array
     */
    protected $renderingStack = [];

    /**
     * Constructor
     *
     * @param RenderingContextInterface|null $context
     */
    public function __construct(RenderingContextInterface $context = null)
    {
        if (!$context) {
            $context = new RenderingContext();
            $context->setControllerName('Default');
            $context->setControllerAction('Default');
        }
        $this->setRenderingContext($context);
    }

    /**
     * Initialize the RenderingContext. This method can be overridden in your
     * View implementation to manipulate the rendering context *before* it is
     * passed during rendering.
     */
    public function initializeRenderingContext()
    {
        $this->baseRenderingContext->getViewHelperVariableContainer()->setView($this);
    }

    /**
     * Sets the cache to use in RenderingContext.
     *
     * @param FluidCacheInterface $cache
     */
    public function setCache(FluidCacheInterface $cache)
    {
        $this->baseRenderingContext->setCache($cache);
    }

    /**
     * Gets the TemplatePaths instance from RenderingContext
     *
     * @return TemplatePaths
     */
    public function getTemplatePaths()
    {
        return $this->baseRenderingContext->getTemplatePaths();
    }

    /**
     * Gets the ViewHelperResolver instance from RenderingContext
     *
     * @return ViewHelperResolver
     */
    public function getViewHelperResolver()
    {
        return $this->baseRenderingContext->getViewHelperResolver();
    }

    /**
     * Gets the RenderingContext used by the View
     *
     * @return RenderingContextInterface
     */
    public function getRenderingContext()
    {
        return $this->baseRenderingContext;
    }

    /**
     * Injects a fresh rendering context
     *
     * @param RenderingContextInterface $renderingContext
     */
    public function setRenderingContext(RenderingContextInterface $renderingContext)
    {
        $this->baseRenderingContext = $renderingContext;
        $this->initializeRenderingContext();
    }

    /**
     * Assign a value to the variable container.
     *
     * @param string $key The key of a view variable to set
     * @param mixed $value The value of the view variable
     * @return $this
     * @api
     */
    public function assign($key, $value)
    {
        $this->baseRenderingContext->getVariableProvider()->add($key, $value);
        return $this;
    }

    /**
     * Assigns multiple values to the JSON output.
     * However, only the key "value" is accepted.
     *
     * @param array $values Keys and values - only a value with key "value" is considered
     * @return $this
     * @api
     */
    public function assignMultiple(array $values)
    {
        $templateVariableContainer = $this->baseRenderingContext->getVariableProvider();
        foreach ($values as $key => $value) {
            $templateVariableContainer->add($key, $value);
        }
        return $this;
    }

    /**
     * Loads the template source and render the template.
     * If "layoutName" is set in a PostParseFacet callback, it will render the file with the given layout.
     *
     * @param string|null $actionName If set, this action's template will be rendered instead of the one defined in the context.
     * @return string Rendered Template
     * @api
     */
    public function render($actionName = null)
    {
        $renderingContext = $this->getCurrentRenderingContext();
        $templateParser = $renderingContext->getTemplateParser();
        $templatePaths = $renderingContext->getTemplatePaths();
        if ($actionName) {
            $actionName = ucfirst($actionName);
            $renderingContext->setControllerAction($actionName);
        }
        try {
            $parsedTemplate = $this->getCurrentParsedTemplate();
        } catch (PassthroughSourceException $error) {
            return $error->getSource();
        }

        if (!$parsedTemplate->hasLayout()) {
            $this->startRendering(self::RENDERING_TEMPLATE, $parsedTemplate, $this->baseRenderingContext);
            $output = $parsedTemplate->render($this->baseRenderingContext);
            $this->stopRendering();
        } else {
            $layoutName = $parsedTemplate->getLayoutName($this->baseRenderingContext);
            try {
                $parsedLayout = $templateParser->getOrParseAndStoreTemplate(
                    $templatePaths->getLayoutIdentifier($layoutName),
                    function ($parent, TemplatePaths $paths) use ($layoutName) {
                        return $paths->getLayoutSource($layoutName);
                    }
                );
            } catch (PassthroughSourceException $error) {
                return $error->getSource();
            }
            $this->startRendering(self::RENDERING_LAYOUT, $parsedTemplate, $this->baseRenderingContext);
            $output = $parsedLayout->render($this->baseRenderingContext);
            $this->stopRendering();
        }

        return $output;
    }

    /**
     * Renders a given section.
     *
     * @param string $sectionName Name of section to render
     * @param array $variables The variables to use
     * @param bool $ignoreUnknown Ignore an unknown section and just return an empty string
     * @return string rendered template for the section
     * @throws InvalidSectionException
     */
    public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false)
    {
        $renderingContext = $this->getCurrentRenderingContext();

        if ($this->getCurrentRenderingType() === self::RENDERING_LAYOUT) {
            // in case we render a layout right now, we will render a section inside a TEMPLATE.
            $renderingTypeOnNextLevel = self::RENDERING_TEMPLATE;
        } else {
            $renderingContext = clone $renderingContext;
            $renderingContext->setVariableProvider($renderingContext->getVariableProvider()->getScopeCopy($variables));
            $renderingTypeOnNextLevel = $this->getCurrentRenderingType();
        }

        try {
            $parsedTemplate = $this->getCurrentParsedTemplate();
        } catch (PassthroughSourceException $error) {
            return $error->getSource();
        } catch (InvalidTemplateResourceException $error) {
            if (!$ignoreUnknown) {
                return $renderingContext->getErrorHandler()->handleViewError($error);
            }
            return '';
        } catch (InvalidSectionException $error) {
            if (!$ignoreUnknown) {
                return $renderingContext->getErrorHandler()->handleViewError($error);
            }
            return '';
        } catch (Exception $error) {
            return $renderingContext->getErrorHandler()->handleViewError($error);
        }

        if ($parsedTemplate->isCompiled()) {
            $methodNameOfSection = 'section_' . sha1($sectionName);
            if (!method_exists($parsedTemplate, $methodNameOfSection)) {
                if ($ignoreUnknown) {
                    return '';
                }
                return $renderingContext->getErrorHandler()->handleViewError(
                    new InvalidSectionException('Section "' . $sectionName . '" does not exist.')
                );
            }
            $this->startRendering($renderingTypeOnNextLevel, $parsedTemplate, $renderingContext);
            $output = $parsedTemplate->$methodNameOfSection($renderingContext);
            $this->stopRendering();
        } else {
            $sections = $parsedTemplate->getVariableContainer()->get('1457379500_sections');
            if (!isset($sections[$sectionName])) {
                if ($ignoreUnknown) {
                    return '';
                }
                return $renderingContext->getErrorHandler()->handleViewError(
                    new InvalidSectionException('Section "' . $sectionName . '" does not exist.')
                );
            }
            /** @var ViewHelperNode $section */
            $section = $sections[$sectionName];

            $renderingContext->getViewHelperVariableContainer()->add(
                SectionViewHelper::class,
                'isCurrentlyRenderingSection',
                true
            );

            $this->startRendering($renderingTypeOnNextLevel, $parsedTemplate, $renderingContext);
            $output = $section->evaluate($renderingContext);
            $this->stopRendering();
        }

        return $output;
    }

    /**
     * Renders a partial.
     *
     * @param string $partialName
     * @param string $sectionName
     * @param array $variables
     * @param bool $ignoreUnknown Ignore an unknown section and just return an empty string
     * @return string
     */
    public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false)
    {
        $templatePaths = $this->baseRenderingContext->getTemplatePaths();
        $renderingContext = clone $this->getCurrentRenderingContext();
        try {
            $parsedPartial = $renderingContext->getTemplateParser()->getOrParseAndStoreTemplate(
                $templatePaths->getPartialIdentifier($partialName),
                function ($parent, TemplatePaths $paths) use ($partialName) {
                    return $paths->getPartialSource($partialName);
                }
            );
        } catch (PassthroughSourceException $error) {
            return $error->getSource();
        } catch (InvalidTemplateResourceException $error) {
            if (!$ignoreUnknown) {
                return $renderingContext->getErrorHandler()->handleViewError($error);
            }
            return '';
        } catch (InvalidSectionException $error) {
            if (!$ignoreUnknown) {
                return $renderingContext->getErrorHandler()->handleViewError($error);
            }
            return '';
        } catch (Exception $error) {
            return $renderingContext->getErrorHandler()->handleViewError($error);
        }
        $renderingContext->setVariableProvider($renderingContext->getVariableProvider()->getScopeCopy($variables));
        $this->startRendering(self::RENDERING_PARTIAL, $parsedPartial, $renderingContext);
        if ($sectionName !== null) {
            $output = $this->renderSection($sectionName, $variables, $ignoreUnknown);
        } else {
            $output = $parsedPartial->render($renderingContext);
        }
        $this->stopRendering();
        return $output;
    }

    /**
     * Start a new nested rendering. Pushes the given information onto the $renderingStack.
     *
     * @param int $type one of the RENDERING_* constants
     * @param ParsedTemplateInterface $template
     * @param RenderingContextInterface $context
     */
    protected function startRendering($type, ParsedTemplateInterface $template, RenderingContextInterface $context)
    {
        $this->renderingStack[] = ['type' => $type, 'parsedTemplate' => $template, 'renderingContext' => $context];
    }

    /**
     * Stops the current rendering. Removes one element from the $renderingStack. Make sure to always call this
     * method pair-wise with startRendering().
     */
    protected function stopRendering()
    {
        $this->getCurrentRenderingContext()->getTemplateCompiler()->reset();
        array_pop($this->renderingStack);
    }

    /**
     * Get the current rendering type.
     *
     * @return int one of RENDERING_* constants
     */
    protected function getCurrentRenderingType()
    {
        $currentRendering = end($this->renderingStack);
        return !empty($currentRendering['type']) ? $currentRendering['type'] : self::RENDERING_TEMPLATE;
    }

    /**
     * Get the parsed template which is currently being rendered or compiled.
     *
     * @return ParsedTemplateInterface
     */
    protected function getCurrentParsedTemplate()
    {
        $currentRendering = end($this->renderingStack);
        $renderingContext = $this->getCurrentRenderingContext();
        $parsedTemplate = !empty($currentRendering['parsedTemplate']) ? $currentRendering['parsedTemplate'] : $renderingContext->getTemplateCompiler()->getCurrentlyProcessingState();
        if ($parsedTemplate) {
            return $parsedTemplate;
        }
        $templatePaths = $renderingContext->getTemplatePaths();
        $templateParser = $renderingContext->getTemplateParser();
        $controllerName = $renderingContext->getControllerName();
        $actionName = $renderingContext->getControllerAction();
        $parsedTemplate = $templateParser->getOrParseAndStoreTemplate(
            $templatePaths->getTemplateIdentifier($controllerName, $actionName),
            function ($parent, TemplatePaths $paths) use ($controllerName, $actionName) {
                return $paths->getTemplateSource($controllerName, $actionName);
            }
        );
        if ($parsedTemplate->isCompiled()) {
            $parsedTemplate->addCompiledNamespaces($this->baseRenderingContext);
        }
        return $parsedTemplate;
    }

    /**
     * Get the rendering context which is currently used.
     *
     * @return RenderingContextInterface
     */
    protected function getCurrentRenderingContext()
    {
        $currentRendering = end($this->renderingStack);
        return !empty($currentRendering['renderingContext']) ? $currentRendering['renderingContext'] : $this->baseRenderingContext;
    }
}
