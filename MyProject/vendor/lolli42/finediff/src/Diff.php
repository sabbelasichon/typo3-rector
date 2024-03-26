<?php

declare(strict_types=1);

/*
 * FINE granularity DIFF
 *
 * (c) 2011 Raymond Hill (http://raymondhill.net/blog/?p=441)
 * (c) 2013 Robert Crowe (http://cogpowered.com)
 * (c) 2021 Christian Kuhn
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace cogpowered\FineDiff;

use cogpowered\FineDiff\Granularity\Character;
use cogpowered\FineDiff\Granularity\GranularityInterface;
use cogpowered\FineDiff\Parser\OpcodesInterface;
use cogpowered\FineDiff\Parser\Parser;
use cogpowered\FineDiff\Parser\ParserInterface;
use cogpowered\FineDiff\Render\Html;
use cogpowered\FineDiff\Render\RendererInterface;

/**
 * Diff class.
 */
class Diff
{
    /**
     * @var GranularityInterface
     */
    protected $granularity;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Instantiate a new instance of Diff.
     *
     * @param GranularityInterface|null $granularity Level of diff.
     * @param RendererInterface|null $renderer Diff renderer.
     * @param ParserInterface|null $parser Parser used to generate opcodes.
     */
    public function __construct(GranularityInterface $granularity = null, RendererInterface $renderer = null, ParserInterface $parser = null)
    {
        $this->granularity = $granularity ?? new Character();
        $this->renderer = $renderer ?? new Html();
        $this->parser = $parser ?? new Parser($this->granularity);
    }

    /**
     * Returns the granularity object used by the parser.
     */
    public function getGranularity(): GranularityInterface
    {
        return $this->parser->getGranularity();
    }

    /**
     * Set the granularity level of the parser.
     */
    public function setGranularity(GranularityInterface $granularity): void
    {
        $this->parser->setGranularity($granularity);
    }

    /**
     * Get the render.
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * Set the renderer.
     */
    public function setRenderer(RendererInterface $renderer): void
    {
        $this->renderer = $renderer;
    }

    /**
     * Get the parser responsible for generating the diff/opcodes.
     */
    public function getParser(): ParserInterface
    {
        return $this->parser;
    }

    /**
     * Set the parser.
     */
    public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }

    /**
     * Gets the diff / opcodes between two strings.
     *
     * Returns the opcode diff which can be used for example
     * to generate HTML report of the differences.
     */
    public function getOpcodes(string $from_text, string $to_text): OpcodesInterface
    {
        return $this->parser->parse($from_text, $to_text);
    }

    /**
     * Render the difference between two strings.
     * By default, will return the difference as HTML.
     */
    public function render(string $from_text, string $to_text): string
    {
        // First we need the opcodes
        $opcodes = $this->getOpcodes($from_text, $to_text);
        return $this->renderer->process($from_text, $opcodes);
    }
}
