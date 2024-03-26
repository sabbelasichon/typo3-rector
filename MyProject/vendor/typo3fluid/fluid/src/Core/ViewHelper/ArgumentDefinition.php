<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

/**
 * Argument definition of each view helper argument
 */
class ArgumentDefinition
{
    /**
     * Name of argument
     *
     * @var string
     */
    protected $name;

    /**
     * Type of argument
     *
     * @var string
     */
    protected $type;

    /**
     * Description of argument
     *
     * @var string
     */
    protected $description;

    /**
     * Is argument required?
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Default value for argument
     *
     * @var mixed
     */
    protected $defaultValue;

    /**
     * Escaping instruction, in line with $this->escapeOutput / $this->escapeChildren on ViewHelpers.
     *
     * A value of NULL means "use default behavior" (which is to escape nodes contained in the value).
     *
     * A value of TRUE means "escape unless escaping is disabled" (e.g. if argument is used in a ViewHelper nested
     * within f:format.raw which disables escaping, the argument will not be escaped).
     *
     * A value of FALSE means "never escape argument" (as in behavior of f:format.raw, which supports both passing
     * argument as actual argument or as tag content, but wants neither to be escaped).
     *
     * @var bool|null
     */
    protected $escape;

    /**
     * Constructor for this argument definition.
     *
     * @param string $name Name of argument
     * @param string $type Type of argument
     * @param string $description Description of argument
     * @param bool $required TRUE if argument is required
     * @param mixed $defaultValue Default value
     * @param bool|null $escape Whether or not argument is escaped, or uses default escaping behavior (see class var comment)
     */
    public function __construct($name, $type, $description, $required, $defaultValue = null, $escape = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->required = $required;
        $this->defaultValue = $defaultValue;
        $this->escape = $escape;
    }

    /**
     * Get the name of the argument
     *
     * @return string Name of argument
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the type of the argument
     *
     * @return string Type of argument
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the description of the argument
     *
     * @return string Description of argument
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the optionality of the argument
     *
     * @return bool TRUE if argument is optional
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Get the default value, if set
     *
     * @return mixed Default value
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return bool|null
     */
    public function getEscape()
    {
        return $this->escape;
    }
}
