<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Text Syntax Tree Node - is a container for strings.
 *
 * @internal
 * @todo Make class final.
 */
class TextNode extends AbstractNode
{
    public function __construct(protected readonly string $text)
    {
    }

    /**
     * Return the text associated to the syntax tree. Text from child nodes is
     * appended to the text in the node's own text.
     */
    public function evaluate(RenderingContextInterface $renderingContext): string
    {
        return $this->text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return $this->text;
    }

    public function convert(TemplateCompiler $templateCompiler): array
    {
        return [
            'initialization' => '',
            'execution' => '\'' . str_replace(['\\', '\''], ['\\\\', '\\\''], $this->text) . '\'',
        ];
    }
}
