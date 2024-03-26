<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * A key/value store that can be used by ViewHelpers to communicate between each other.
 *
 * @api
 */
class ViewHelperVariableContainer
{
    /**
     * Two-dimensional object array storing the values. The first dimension is the fully qualified ViewHelper name,
     * and the second dimension is the identifier for the data the ViewHelper wants to store.
     *
     * @var array
     */
    protected $objects = [];

    /**
     * @var ViewInterface
     */
    protected $view;

    /**
     * Add a variable to the Variable Container. Make sure that $viewHelperName is ALWAYS set
     * to your fully qualified ViewHelper Class Name
     *
     * @param string $viewHelperName The ViewHelper Class name (Fully qualified, like "TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper")
     * @param string $key Key of the data
     * @param mixed $value The value to store
     * @api
     */
    public function add($viewHelperName, $key, $value)
    {
        $this->addOrUpdate($viewHelperName, $key, $value);
    }

    /**
     * Adds, or overrides recursively, all current variables defined in associative
     * array or Traversable (with string keys!).
     *
     * @param string $viewHelperName The ViewHelper Class name (Fully qualified, like "TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper")
     * @param array|\Traversable $variables An associative array of all variables to add
     * @api
     */
    public function addAll($viewHelperName, $variables)
    {
        if (!is_array($variables) && !$variables instanceof \Traversable) {
            throw new \InvalidArgumentException(
                'Invalid argument type for $variables in ViewHelperVariableContainer->addAll(). Expects array/Traversable ' .
                'but received ' . (is_object($variables) ? get_class($variables) : gettype($variables)),
                1501425195
            );
        }
        $this->objects[$viewHelperName] = array_replace_recursive(
            isset($this->objects[$viewHelperName]) ? $this->objects[$viewHelperName] : [],
            $variables instanceof \Traversable ? iterator_to_array($variables) : $variables
        );
    }

    /**
     * Add a variable to the Variable Container. Make sure that $viewHelperName is ALWAYS set
     * to your fully qualified ViewHelper Class Name.
     * In case the value is already inside, it is silently overridden.
     *
     * @param string $viewHelperName The ViewHelper Class name (Fully qualified, like "TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper")
     * @param string $key Key of the data
     * @param mixed $value The value to store
     */
    public function addOrUpdate($viewHelperName, $key, $value)
    {
        if (!isset($this->objects[$viewHelperName])) {
            $this->objects[$viewHelperName] = [];
        }
        $this->objects[$viewHelperName][$key] = $value;
    }

    /**
     * Gets a variable which is stored
     *
     * @param string $viewHelperName The ViewHelper Class name (Fully qualified, like "TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper")
     * @param string $key Key of the data
     * @param mixed $default Default value to use if no value is found.
     * @return mixed The object stored
     * @api
     */
    public function get($viewHelperName, $key, $default = null)
    {
        return $this->exists($viewHelperName, $key) ? $this->objects[$viewHelperName][$key] : $default;
    }

    /**
     * Gets all variables stored for a particular ViewHelper
     *
     * @param string $viewHelperName The ViewHelper Class name (Fully qualified, like "TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper")
     * @param mixed $default
     * @return array
     */
    public function getAll($viewHelperName, $default = null)
    {
        return array_key_exists($viewHelperName, $this->objects) ? $this->objects[$viewHelperName] : $default;
    }

    /**
     * Determine whether there is a variable stored for the given key
     *
     * @param string $viewHelperName The ViewHelper Class name (Fully qualified, like "TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper")
     * @param string $key Key of the data
     * @return bool TRUE if a value for the given ViewHelperName / Key is stored, FALSE otherwise.
     * @api
     */
    public function exists($viewHelperName, $key)
    {
        return isset($this->objects[$viewHelperName]) && array_key_exists($key, $this->objects[$viewHelperName]);
    }

    /**
     * Remove a value from the variable container
     *
     * @param string $viewHelperName The ViewHelper Class name (Fully qualified, like "TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper")
     * @param string $key Key of the data to remove
     * @api
     */
    public function remove($viewHelperName, $key)
    {
        unset($this->objects[$viewHelperName][$key]);
    }

    /**
     * Set the view to pass it to ViewHelpers.
     *
     * @param ViewInterface $view View to set
     */
    public function setView(ViewInterface $view)
    {
        $this->view = $view;
    }

    /**
     * Get the view.
     *
     * !!! This is NOT a public API and might still change!!!
     *
     * @return ViewInterface|null The View, or null if view was not set
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Clean up for serializing.
     *
     * @return array
     */
    public function __sleep()
    {
        return ['objects'];
    }
}
