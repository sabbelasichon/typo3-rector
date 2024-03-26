<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

/**
 * Tag builder. Can be easily accessed in AbstractTagBasedViewHelper
 *
 * @api
 */
class TagBuilder
{
    /**
     * Name of the Tag to be rendered
     *
     * @var string
     */
    protected $tagName = '';

    /**
     * Content of the tag to be rendered
     *
     * @var string
     */
    protected $content = '';

    /**
     * Attributes of the tag to be rendered
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Specifies whether this tag needs a closing tag.
     * E.g. <textarea> cant be self-closing even if its empty
     *
     * @var bool
     */
    protected $forceClosingTag = false;

    /**
     * @var bool
     */
    protected $ignoreEmptyAttributes = false;

    /**
     * Constructor
     *
     * @param string $tagName name of the tag to be rendered
     * @param string $tagContent content of the tag to be rendered
     * @api
     */
    public function __construct($tagName = '', $tagContent = '')
    {
        $this->setTagName($tagName);
        $this->setContent($tagContent);
    }

    /**
     * Sets the tag name
     *
     * @param string $tagName name of the tag to be rendered
     * @api
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * Gets the tag name
     *
     * @return string tag name of the tag to be rendered
     * @api
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Sets the content of the tag
     *
     * @param string $tagContent content of the tag to be rendered
     * @api
     */
    public function setContent($tagContent)
    {
        $this->content = $tagContent;
    }

    /**
     * Gets the content of the tag
     *
     * @return string content of the tag to be rendered
     * @api
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns TRUE if tag contains content, otherwise FALSE
     *
     * @return bool TRUE if tag contains text, otherwise FALSE
     * @api
     */
    public function hasContent()
    {
        return $this->content !== '' && $this->content !== null;
    }

    /**
     * Set this to TRUE to force a closing tag
     * E.g. <textarea> cant be self-closing even if its empty
     *
     * @param bool $forceClosingTag
     * @api
     */
    public function forceClosingTag($forceClosingTag)
    {
        $this->forceClosingTag = $forceClosingTag;
    }

    /**
     * Returns TRUE if the tag has an attribute with the given name
     *
     * @param string $attributeName name of the attribute
     * @return bool TRUE if the tag has an attribute with the given name, otherwise FALSE
     * @api
     */
    public function hasAttribute($attributeName)
    {
        return array_key_exists($attributeName, $this->attributes);
    }

    /**
     * Get an attribute from the $attributes-collection
     *
     * @param string $attributeName name of the attribute
     * @return string|null The attribute value or NULL if the attribute is not registered
     * @api
     */
    public function getAttribute($attributeName)
    {
        if (!$this->hasAttribute($attributeName)) {
            return null;
        }
        return $this->attributes[$attributeName];
    }

    /**
     * Get all attribute from the $attributes-collection
     *
     * @return array Attributes indexed by attribute name
     * @api
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param bool $ignoreEmptyAttributes
     */
    public function ignoreEmptyAttributes($ignoreEmptyAttributes)
    {
        $this->ignoreEmptyAttributes = $ignoreEmptyAttributes;
        if ($ignoreEmptyAttributes) {
            $this->attributes = array_filter($this->attributes, function ($item) { return trim((string)$item) !== ''; });
        }
    }

    /**
     * Adds an attribute to the $attributes-collection
     *
     * @param string $attributeName name of the attribute to be added to the tag
     * @param string|\Traversable|array|null $attributeValue attribute value
     * @param bool $escapeSpecialCharacters apply htmlspecialchars to attribute value
     * @api
     */
    public function addAttribute($attributeName, $attributeValue, $escapeSpecialCharacters = true)
    {
        if ($escapeSpecialCharacters) {
            $attributeName = htmlspecialchars($attributeName);
        }
        if (in_array($attributeName, ['data', 'aria'], true)
            && (is_array($attributeValue) || $attributeValue instanceof \Traversable)
        ) {
            foreach ($attributeValue as $name => $value) {
                $this->addAttribute($attributeName . '-' . $name, $value, $escapeSpecialCharacters);
            }
        } else {
            if (trim((string)$attributeValue) === '' && $this->ignoreEmptyAttributes) {
                return;
            }
            if ($escapeSpecialCharacters) {
                $attributeValue = htmlspecialchars((string)$attributeValue);
            }
            $this->attributes[$attributeName] = $attributeValue;
        }
    }

    /**
     * Adds attributes to the $attributes-collection
     *
     * @param array $attributes collection of attributes to add. key = attribute name, value = attribute value
     * @param bool $escapeSpecialCharacters apply htmlspecialchars to attribute values#
     * @api
     */
    public function addAttributes(array $attributes, $escapeSpecialCharacters = true)
    {
        foreach ($attributes as $attributeName => $attributeValue) {
            $this->addAttribute($attributeName, $attributeValue, $escapeSpecialCharacters);
        }
    }

    /**
     * Removes an attribute from the $attributes-collection
     *
     * @param string $attributeName name of the attribute to be removed from the tag
     * @api
     */
    public function removeAttribute($attributeName)
    {
        unset($this->attributes[$attributeName]);
    }

    /**
     * Resets the TagBuilder by setting all members to their default value
     *
     * @api
     */
    public function reset()
    {
        $this->tagName = '';
        $this->content = '';
        $this->attributes = [];
        $this->forceClosingTag = false;
    }

    /**
     * Renders and returns the tag
     *
     * @return string
     * @api
     */
    public function render()
    {
        if (empty($this->tagName)) {
            return '';
        }
        $output = '<' . $this->tagName;
        foreach ($this->attributes as $attributeName => $attributeValue) {
            $output .= ' ' . $attributeName . '="' . $attributeValue . '"';
        }
        if ($this->hasContent() || $this->forceClosingTag) {
            $output .= '>' . $this->content . '</' . $this->tagName . '>';
        } else {
            $output .= ' />';
        }
        return $output;
    }
}
