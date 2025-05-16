<?php

namespace TYPO3Fluid\Fluid\Core\Variables;

if (interface_exists('TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface')) {
    return;
}

interface VariableProviderInterface extends \ArrayAccess
{
    /**
     * Gets a fresh instance of this type of VariableProvider
     * and fills it with the variables passed in $variables.
     *
     * Can be overridden to enable special instance creation
     * of the new VariableProvider as well as take care of any
     * automatically transferred variables (in the default
     * implementation the $settings variable is transferred).
     */
    public function getScopeCopy($variables): VariableProviderInterface;

    /**
     * Set the source data used by this VariableProvider. The
     * source can be any type, but the type must of course be
     * supported by the VariableProvider itself.
     */
    public function setSource($source): void;

    public function getSource();

    /**
     * Get every variable provisioned by the VariableProvider
     * implementing the interface. Must return an array or
     * ArrayAccess instance!
     */
    public function getAll();

    /**
     * Add a variable to the context
     *
     * @param string $identifier Identifier of the variable to add
     * @param mixed $value The variable's value
     * @api
     */
    public function add(string $identifier, $value): void;

    /**
     * Get a variable from the context.
     *
     * @return mixed The variable value identified by $identifier
     * @api
     */
    public function get(string $identifier);

    /**
     * Get a variable by dotted path expression, retrieving the
     * variable from nested arrays/objects one segment at a time.
     */
    public function getByPath(string $path);

    /**
     * Remove a variable from context.
     *
     * @param string $identifier The identifier to remove
     * @api
     */
    public function remove(string $identifier): void;

    /**
     * Returns an array of all identifiers available in the context.
     *
     * @return string[] Array of identifier strings
     */
    public function getAllIdentifiers(): array;

    /**
     * Checks if this property exists in the VariableContainer.
     *
     * @return bool true if $identifier exists
     * @api
     */
    public function exists(string $identifier): bool;

    /**
     * Adds a variable to the context.
     */
    public function offsetSet($identifier, $value): void;

    /**
     * Remove a variable from context.
     */
    public function offsetUnset($identifier): void;

    /**
     * Checks if this property exists in the VariableContainer.
     */
    public function offsetExists($identifier): bool;

    /**
     * Get a variable from the context.
     */
    public function offsetGet($identifier);
}
