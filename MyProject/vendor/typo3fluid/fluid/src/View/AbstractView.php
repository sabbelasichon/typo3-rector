<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\View;

/**
 * An abstract View
 *
 * @api
 */
abstract class AbstractView implements ViewInterface
{
    /**
     * View variables and their values
     * @var array
     * @see assign()
     */
    protected $variables = [];

    /**
     * Renders the view
     *
     * @return string The rendered view
     * @api
     */
    public function render()
    {
        return '';
    }

    /**
     * Add a variable to $this->variables.
     * Can be chained, so $this->view->assign(..., ...)->assign(..., ...); is possible
     *
     * @param string $key Key of variable
     * @param mixed $value Value of object
     * @return $this
     * @api
     */
    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
        return $this;
    }

    /**
     * Add multiple variables to $this->variables.
     *
     * @param array $values array in the format array(key1 => value1, key2 => value2)
     * @return AbstractView an instance of $this, to enable chaining
     * @api
     */
    public function assignMultiple(array $values)
    {
        foreach ($values as $key => $value) {
            $this->assign($key, $value);
        }
        return $this;
    }
}
