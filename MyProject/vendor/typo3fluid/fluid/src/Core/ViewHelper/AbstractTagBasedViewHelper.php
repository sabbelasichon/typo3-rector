<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

/**
 * Tag based view helper.
 * Should be used as the base class for all view helpers which output simple tags, as it provides some
 * convenience methods to register default attributes, ...
 *
 * @api
 */
abstract class AbstractTagBasedViewHelper extends AbstractViewHelper
{
    /**
     * Disable escaping of tag based ViewHelpers so that the rendered tag is not htmlspecialchar'd
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Names of all registered tag attributes
     *
     * @var array
     */
    private static $tagAttributes = [];

    /**
     * Tag builder instance
     *
     * @var TagBuilder
     * @api
     */
    protected $tag;

    /**
     * Name of the tag to be created by this view helper
     *
     * @var string
     * @api
     */
    protected $tagName = 'div';

    /**
     * Arguments which are valid but do not have an ArgumentDefinition, e.g.
     * data- prefixed arguments.
     *
     * @var array
     */
    protected $additionalArguments = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTagBuilder(new TagBuilder($this->tagName));
    }

    /**
     * @param TagBuilder $tag
     */
    public function setTagBuilder(TagBuilder $tag)
    {
        $this->tag = $tag;
        $this->tag->setTagName($this->tagName);
    }

    /**
     * Constructor
     *
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('additionalAttributes', 'array', 'Additional tag attributes. They will be added directly to the resulting HTML tag.', false);
        $this->registerArgument('data', 'array', 'Additional data-* attributes. They will each be added with a "data-" prefix.', false);
        $this->registerArgument('aria', 'array', 'Additional aria-* attributes. They will each be added with a "aria-" prefix.', false);
    }

    /**
     * Sets the tag name to $this->tagName.
     * Additionally, sets all tag attributes which were registered in
     * $this->tagAttributes and additionalArguments.
     *
     * Will be invoked just before the render method.
     *
     * @api
     */
    public function initialize()
    {
        parent::initialize();
        $this->tag->reset();
        $this->tag->setTagName($this->tagName);

        if ($this->hasArgument('additionalAttributes') && is_array($this->arguments['additionalAttributes'])) {
            $this->tag->addAttributes($this->arguments['additionalAttributes']);
        }

        if ($this->hasArgument('data') && is_array($this->arguments['data'])) {
            foreach ($this->arguments['data'] as $dataAttributeKey => $dataAttributeValue) {
                $this->tag->addAttribute('data-' . $dataAttributeKey, $dataAttributeValue);
            }
        }

        if ($this->hasArgument('aria') && is_array($this->arguments['aria'])) {
            foreach ($this->arguments['aria'] as $ariaAttributeKey => $ariaAttributeValue) {
                $this->tag->addAttribute('aria-' . $ariaAttributeKey, $ariaAttributeValue);
            }
        }

        foreach ($this->additionalArguments as $argumentName => $argumentValue) {
            if (strpos($argumentName, 'data-') === 0 || strpos($argumentName, 'aria-') === 0) {
                $this->tag->addAttribute($argumentName, $argumentValue);
            }
        }

        if (isset(self::$tagAttributes[get_class($this)])) {
            foreach (self::$tagAttributes[get_class($this)] as $attributeName) {
                if ($this->hasArgument($attributeName) && $this->arguments[$attributeName] !== '') {
                    $this->tag->addAttribute($attributeName, $this->arguments[$attributeName]);
                }
            }
        }
    }

    /**
     * Register a new tag attribute. Tag attributes are all arguments which will be directly appended to a tag if you call $this->initializeTag()
     *
     * @param string $name Name of tag attribute
     * @param string $type Type of the tag attribute
     * @param string $description Description of tag attribute
     * @param bool $required set to TRUE if tag attribute is required. Defaults to FALSE.
     * @param mixed $defaultValue Optional, default value of attribute if one applies
     * @api
     */
    protected function registerTagAttribute($name, $type, $description, $required = false, $defaultValue = null)
    {
        $this->registerArgument($name, $type, $description, $required, $defaultValue);
        self::$tagAttributes[get_class($this)][$name] = $name;
    }

    /**
     * Registers all standard HTML universal attributes.
     * Should be used inside registerArguments();
     *
     * @api
     */
    protected function registerUniversalTagAttributes()
    {
        $this->registerTagAttribute('class', 'string', 'CSS class(es) for this element');
        $this->registerTagAttribute('dir', 'string', 'Text direction for this HTML element. Allowed strings: "ltr" (left to right), "rtl" (right to left)');
        $this->registerTagAttribute('id', 'string', 'Unique (in this file) identifier for this HTML element.');
        $this->registerTagAttribute('lang', 'string', 'Language for this element. Use short names specified in RFC 1766');
        $this->registerTagAttribute('style', 'string', 'Individual CSS styles for this element');
        $this->registerTagAttribute('title', 'string', 'Tooltip text of element');
        $this->registerTagAttribute('accesskey', 'string', 'Keyboard shortcut to access this element');
        $this->registerTagAttribute('tabindex', 'integer', 'Specifies the tab order of this element');
        $this->registerTagAttribute('onclick', 'string', 'JavaScript evaluated for the onclick event');
    }

    public function handleAdditionalArguments(array $arguments)
    {
        $this->additionalArguments = $arguments;
        parent::handleAdditionalArguments($arguments);
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->tag->render();
    }
}
