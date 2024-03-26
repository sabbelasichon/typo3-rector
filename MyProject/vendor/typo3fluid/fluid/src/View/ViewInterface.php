<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\View;

/**
 * Interface of a view
 *
 * @api
 */
interface ViewInterface
{
    /**
     * Add a variable to the view data collection.
     * Can be chained, so $this->view->assign(..., ...)->assign(..., ...); is possible
     *
     * @param string $key Key of variable
     * @param mixed $value Value of object
     * @return ViewInterface an instance of $this, to enable chaining
     * @api
     */
    public function assign($key, $value);

    /**
     * Add multiple variables to the view data collection
     *
     * @param array $values array in the format array(key1 => value1, key2 => value2)
     * @return ViewInterface an instance of $this, to enable chaining
     * @api
     */
    public function assignMultiple(array $values);

    /**
     * Renders the view
     *
     * @return string The rendered view
     * @api
     */
    public function render();

    /**
     * Renders a given section.
     *
     * @param string $sectionName Name of section to render
     * @param array $variables The variables to use
     * @param bool $ignoreUnknown Ignore an unknown section and just return an empty string
     * @return string rendered template for the section
     * @throws Exception\InvalidSectionException
     */
    public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false);

    /**
     * Renders a partial.
     *
     * @param string $partialName
     * @param string $sectionName
     * @param array $variables
     * @param bool $ignoreUnknown Ignore an unknown section and just return an empty string
     * @return string
     */
    public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false);
}
