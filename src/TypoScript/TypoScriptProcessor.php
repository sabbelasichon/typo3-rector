<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript;

use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Helmich\TypoScriptParser\Tokenizer\TokenizerException;
use Rector\ChangesReporting\Application\ErrorAndDiffCollector;
use Symfony\Component\Console\Output\BufferedOutput;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @see \Ssch\TYPO3Rector\Tests\TypoScript\TypoScriptProcessorTest
 */
final class TypoScriptProcessor
{
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var ParserInterface
     */
    private $typoscriptParser;

    /**
     * @var ASTPrinterInterface
     */
    private $typoscriptPrinter;

    /**
     * @var BufferedOutput
     */
    private $output;

    /**
     * @var Visitor[]
     */
    private $visitors = [];

    /**
     * @var ErrorAndDiffCollector
     */
    private $errorAndDiffCollector;

    /**
     * @param Visitor[] $visitors
     */
    public function __construct(
        SmartFileSystem $smartFileSystem,
        ParserInterface $typoscriptParser,
        BufferedOutput $output,
        ASTPrinterInterface $typoscriptPrinter,
        ErrorAndDiffCollector $errorAndDiffCollector,
        array $visitors = []
    ) {
        $this->smartFileSystem = $smartFileSystem;
        $this->typoscriptParser = $typoscriptParser;

        $this->typoscriptPrinter = $typoscriptPrinter;
        $this->output = $output;
        $this->visitors = $visitors;

        $this->errorAndDiffCollector = $errorAndDiffCollector;
    }

    public function process(string $typoScriptFilePath): ?string
    {
        if (! $this->smartFileSystem->exists($typoScriptFilePath)) {
            return null;
        }

        $smartFileInfo = new SmartFileInfo($typoScriptFilePath);

        try {
            $originalStatements = $this->typoscriptParser->parseString($smartFileInfo->getContents());

            $traverser = new Traverser($originalStatements);
            foreach ($this->visitors as $visitor) {
                $traverser->addVisitor($visitor);
            }
            $traverser->walk();

            $this->typoscriptPrinter->printStatements($originalStatements, $this->output);

            $typoScriptContent = $this->output->fetch();

            $this->errorAndDiffCollector->addFileDiff(
                $smartFileInfo,
                $typoScriptContent,
                $smartFileInfo->getContents()
            );

            return $typoScriptContent;
        } catch (TokenizerException $tokenizerException) {
            return null;
        }
    }
}
