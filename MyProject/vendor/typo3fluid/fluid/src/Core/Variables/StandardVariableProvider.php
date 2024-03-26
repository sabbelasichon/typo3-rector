<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Variables;

/**
 * Class StandardVariableProvider
 */
class StandardVariableProvider implements VariableProviderInterface
{
    /**
     * @deprecated Unused. Will be removed.
     */
    public const ACCESSOR_ARRAY = 'array';
    /**
     * @deprecated Unused. Will be removed.
     */
    public const ACCESSOR_GETTER = 'getter';
    /**
     * @deprecated Unused. Will be removed.
     */
    public const ACCESSOR_ASSERTER = 'asserter';
    /**
     * @deprecated Unused. Will be removed.
     */
    public const ACCESSOR_PUBLICPROPERTY = 'public';

    /**
     * Variables stored in context
     *
     * @var mixed
     */
    protected $variables = [];

    /**
     * Variables, if any, with which to initialize this
     * VariableProvider.
     *
     * @param array $variables
     */
    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    /**
     * @param array|\ArrayAccess $variables
     * @return VariableProviderInterface
     */
    public function getScopeCopy($variables)
    {
        if (!array_key_exists('settings', $variables) && array_key_exists('settings', $this->variables)) {
            $variables['settings'] = $this->variables['settings'];
        }
        $className = get_class($this);
        return new $className($variables);
    }

    /**
     * Set the source data used by this VariableProvider. The
     * source can be any type, but the type must of course be
     * supported by the VariableProvider itself.
     *
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->variables = $source;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->variables;
    }

    /**
     * Get every variable provisioned by the VariableProvider
     * implementing the interface. Must return an array or
     * ArrayAccess instance!
     *
     * @return array|\ArrayAccess
     */
    public function getAll()
    {
        return $this->variables;
    }

    /**
     * Add a variable to the context
     *
     * @param string $identifier Identifier of the variable to add
     * @param mixed $value The variable's value
     * @api
     */
    public function add($identifier, $value)
    {
        $this->variables[$identifier] = $value;
    }

    /**
     * Get a variable from the context. Throws exception if variable is not found in context.
     *
     * If "_all" is given as identifier, all variables are returned in an array,
     * if one of the other reserved variables are given, their appropriate value
     * they're representing is returned.
     *
     * @param string $identifier
     * @return mixed The variable value identified by $identifier
     * @api
     */
    public function get($identifier)
    {
        return $this->getByPath($identifier);
    }

    /**
     * Get a variable by dotted path expression, retrieving the
     * variable from nested arrays/objects one segment at a time.
     * If the second variable is passed, it is expected to contain
     * extraction method names (constants from this class)
     * which indicate how each value is extracted.
     *
     * @param string $path
     * @return mixed
     */
    public function getByPath($path)
    {
        $subject = $this->variables;
        $subVariableReferences = explode('.', $this->resolveSubVariableReferences($path));
        foreach ($subVariableReferences as $pathSegment) {
            if ((is_array($subject) && array_key_exists($pathSegment, $subject))
                || ($subject instanceof \ArrayAccess && $subject->offsetExists($pathSegment))
            ) {
                $subject = $subject[$pathSegment];
                continue;
            }
            if (is_object($subject)) {
                $upperCasePropertyName = ucfirst($pathSegment);
                $getMethod = 'get' . $upperCasePropertyName;
                if (method_exists($subject, $getMethod)) {
                    $subject = $subject->$getMethod();
                    continue;
                }
                $isMethod = 'is' . $upperCasePropertyName;
                if (method_exists($subject, $isMethod)) {
                    $subject = $subject->$isMethod();
                    continue;
                }
                $hasMethod = 'has' . $upperCasePropertyName;
                if (method_exists($subject, $hasMethod)) {
                    $subject = $subject->$hasMethod();
                    continue;
                }
                if (property_exists($subject, $pathSegment)) {
                    $subject = $subject->$pathSegment;
                    continue;
                }
            }
            return null;
        }
        return $subject;
    }

    /**
     * Remove a variable from context. Throws exception if variable is not found in context.
     *
     * @param string $identifier The identifier to remove
     * @api
     */
    public function remove($identifier)
    {
        if (array_key_exists($identifier, $this->variables)) {
            unset($this->variables[$identifier]);
        }
    }

    /**
     * Returns an array of all identifiers available in the context.
     *
     * @return array Array of identifier strings
     */
    public function getAllIdentifiers()
    {
        return array_keys($this->variables);
    }

    /**
     * Checks if this property exists in the VariableContainer.
     *
     * @param string $identifier
     * @return bool TRUE if $identifier exists, FALSE otherwise
     * @api
     */
    public function exists($identifier)
    {
        return array_key_exists($identifier, $this->variables);
    }

    /**
     * Clean up for serializing.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return ['variables'];
    }

    /**
     * Adds a variable to the context.
     *
     * @param string $identifier Identifier of the variable to add
     * @param mixed $value The variable's value
     */
    public function offsetSet($identifier, $value)
    {
        $this->add($identifier, $value);
    }

    /**
     * Remove a variable from context. Throws exception if variable is not found in context.
     *
     * @param string $identifier The identifier to remove
     */
    public function offsetUnset($identifier)
    {
        $this->remove($identifier);
    }

    /**
     * Checks if this property exists in the VariableContainer.
     *
     * @param string $identifier
     * @return bool TRUE if $identifier exists, FALSE otherwise
     */
    public function offsetExists($identifier)
    {
        return $this->exists($identifier);
    }

    /**
     * Get a variable from the context. Throws exception if variable is not found in context.
     *
     * @param string $identifier
     * @return mixed The variable identified by $identifier
     */
    public function offsetGet($identifier)
    {
        return $this->get($identifier);
    }

    protected function resolveSubVariableReferences(string $propertyPath): string
    {
        if (strpos($propertyPath, '{') !== false) {
            // https://www.pcre.org/original/doc/html/pcrepattern.html#SEC1
            // https://stackoverflow.com/questions/546433/regular-expression-to-match-balanced-parentheses
            // https://stackoverflow.com/questions/524548/regular-expression-to-detect-semi-colon-terminated-c-for-while-loops/524624#524624
            // @todo: We're dealing with both *parallel* and *nested* curly braces here. It *might* be better to
            //        substitute the regex with a char-based parser that counts opening vs. closing braces as
            //        mentioned in the links above. Instead, we're currently using a backtracking recursive regex.
            preg_match_all('/{[^}{]*+(?:(?R)[^}{]*)*+}/', $propertyPath, $matches);
            foreach ($matches[0] as $match) {
                $subPropertyPath = substr($match, 1, -1);
                $subPropertyValue = $this->getByPath($subPropertyPath);
                if ($subPropertyValue !== null) {
                    $propertyPath = str_replace($match, $subPropertyValue, $propertyPath);
                }
            }
        }
        return $propertyPath;
    }
}
