<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * A node which handles object access. This means it handles structures like {object.accessor.bla}
 *
 * @internal
 * @todo Make class final.
 */
class ObjectAccessorNode extends AbstractNode
{
    /**
     * Object path which will be called. Is a list like "post.name.email"
     *
     * @var string
     */
    protected $objectPath;

    /**
     * Constructor. Takes an object path as input.
     *
     * The first part of the object path has to be a variable in the
     * VariableProvider.
     *
     * @param string $objectPath An Object Path, like object1.object2.object3
     */
    public function __construct($objectPath)
    {
        $this->objectPath = $objectPath;
    }

    /**
     * @internal Internally used for building up cached templates; do not use directly!
     * @return string
     */
    public function getObjectPath()
    {
        return $this->objectPath;
    }

    /**
     * @deprecated Unused. Will be removed.
     */
    public function getAccessors(): array
    {
        return [];
    }

    /**
     * Evaluate this node and return the correct object.
     *
     * Handles each part (denoted by .) in $this->objectPath in the following order:
     * - call appropriate getter
     * - call public property, if exists
     * - fail
     *
     * The first part of the object path has to be a variable in the
     * VariableProvider.
     *
     * @param RenderingContextInterface $renderingContext
     * @return mixed The evaluated object, can be any object type.
     */
    public function evaluate(RenderingContextInterface $renderingContext)
    {
        $objectPath = strtolower($this->objectPath);
        $variableProvider = $renderingContext->getVariableProvider();
        if ($objectPath === '_all') {
            return $variableProvider->getAll();
        }
        return $variableProvider->getByPath($this->objectPath);
    }

    public function convert(TemplateCompiler $templateCompiler): array
    {
        $path = $this->objectPath;
        if ($path === '_all') {
            return [
                'initialization' => '',
                'execution' => '$renderingContext->getVariableProvider()->getAll()',
            ];
        }
        return [
            'initialization' => '',
            'execution' => sprintf(
                '$renderingContext->getVariableProvider()->getByPath(\'%s\')',
                $path
            )
        ];
    }
}
