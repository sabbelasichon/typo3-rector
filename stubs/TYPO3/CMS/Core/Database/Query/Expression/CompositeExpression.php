<?php

namespace TYPO3\CMS\Core\Database\Query\Expression;

if (class_exists('TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression')) {
    return;
}

class CompositeExpression
{
    /**
     * Constant that represents an AND composite expression.
     */
    public const TYPE_AND = 'AND';

    /**
     * Constant that represents an OR composite expression.
     */
    public const TYPE_OR = 'OR';


    public function __construct($type, array $parts = [])
    {
    }

    public static function and($part=null, ...$parts): self
    {
        return (new self(self::TYPE_AND, []));
    }

    public static function or($part=null, ...$parts): self
    {
        return (new self(self::TYPE_OR, []));
    }

    /**
     * @return $this
     */
    public function add($part)
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function addMultiple(array $parts = [])
    {
        return $this;
    }

    public function with($part, ...$parts): self
    {
        return $this;
    }
}
