<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Variables;

/**
 * Interface VariableProviderInterface
 *
 * Implemented by classes able to provide variables
 * for a Fluid template rendering.
 *
 * Your VariableProvider implementation does not
 * have to allow setting variables or use the
 * constructor variables argument for anything, but
 * should at least implement the getting methods.
 */
interface VariableProviderInterface extends \ArrayAccess
{
    /**
     * Variables, if any, with which to initialize this
     * VariableProvider.
     *
     * @param array $variables
     * @todo: This must be removed from the interface! At the moment,
     *        StandardVariableProvider accepts variables as constructor
     *        arguments, while ChainedVariableProvider expects an array
     *        of sub providers as constructor argument.
     *        Thus, setSource() should be the only way to set variables
     *        and StandardVariableProvider *must not* accept current
     *        variables as constructor argument.
     *        Adding variables as constructor must not be relied on!
     */
    public function __construct(array $variables = []);

    /**
     * Gets a fresh instance of this type of VariableProvider
     * and fills it with the variables passed in $variables.
     *
     * Can be overridden to enable special instance creation
     * of the new VariableProvider as well as take care of any
     * automatically transferred variables (in the default
     * implementation the $settings variable is transferred).
     *
     * @param array|\ArrayAccess $variables
     * @return VariableProviderInterface
     */
    public function getScopeCopy($variables);

    /**
     * Set the source data used by this VariableProvider. The
     * source can be any type, but the type must of course be
     * supported by the VariableProvider itself.
     *
     * @param mixed $source
     */
    public function setSource($source);

    /**
     * @return mixed
     */
    public function getSource();

    /**
     * Get every variable provisioned by the VariableProvider
     * implementing the interface. Must return an array or
     * ArrayAccess instance!
     *
     * @return array|\ArrayAccess
     */
    public function getAll();

    /**
     * Add a variable to the context
     *
     * @param string $identifier Identifier of the variable to add
     * @param mixed $value The variable's value
     * @api
     */
    public function add($identifier, $value);

    /**
     * Get a variable from the context.
     *
     * @param string $identifier
     * @return mixed The variable value identified by $identifier
     * @api
     */
    public function get($identifier);

    /**
     * Get a variable by dotted path expression, retrieving the
     * variable from nested arrays/objects one segment at a time.
     *
     * @param string $path
     * @return mixed
     */
    public function getByPath($path);

    /**
     * Remove a variable from context.
     *
     * @param string $identifier The identifier to remove
     * @api
     */
    public function remove($identifier);

    /**
     * Returns an array of all identifiers available in the context.
     *
     * @return array Array of identifier strings
     */
    public function getAllIdentifiers();

    /**
     * Checks if this property exists in the VariableContainer.
     *
     * @param string $identifier
     * @return bool TRUE if $identifier exists, FALSE otherwise
     * @api
     */
    public function exists($identifier);

    /**
     * Adds a variable to the context.
     *
     * @param string $identifier Identifier of the variable to add
     * @param mixed $value The variable's value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($identifier, $value);

    /**
     * Remove a variable from context.
     *
     * @param string $identifier The identifier to remove
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($identifier);

    /**
     * Checks if this property exists in the VariableContainer.
     *
     * @param string $identifier
     * @return bool TRUE if $identifier exists, FALSE otherwise
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($identifier);

    /**
     * Get a variable from the context.
     *
     * @param string $identifier
     * @return mixed The variable identified by $identifier
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($identifier);
}
