<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript;

use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinterConfiguration;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Helmich\TypoScriptParser\Tokenizer\TokenizerException;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\EditorConfig\EditorConfigParser;
use Ssch\TYPO3Rector\Processor\ConfigurableProcessorInterface;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @see \Ssch\TYPO3Rector\Tests\TypoScript\TypoScriptProcessorTest
 */
final class TypoScriptProcessor implements ConfigurableProcessorInterface
{
    /**
     * @var string
     */
    public const ALLOWED_FILE_EXTENSIONS = 'allowed_file_extensions';

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
     * @var string[]
     */
    private $allowedFileExtensions = ['typoscript', 'ts', 'txt'];

    /**
     * @var CurrentFileProvider
     */
    private $currentFileProvider;

    /**
     * @var EditorConfigParser
     */
    private $editorConfigParser;

    /**
     * @param Visitor[] $visitors
     */
    public function __construct(
        ParserInterface $typoscriptParser,
        BufferedOutput $output,
        ASTPrinterInterface $typoscriptPrinter,
        CurrentFileProvider $currentFileProvider,
        EditorConfigParser $editorConfigParser,
        array $visitors = []
    ) {
        $this->typoscriptParser = $typoscriptParser;

        $this->typoscriptPrinter = $typoscriptPrinter;
        $this->output = $output;
        $this->visitors = $visitors;
        $this->currentFileProvider = $currentFileProvider;
        $this->editorConfigParser = $editorConfigParser;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    public function supports(File $file): bool
    {
        if ([] === $this->visitors) {
            return false;
        }

        $smartFileInfo = $file->getSmartFileInfo();

        return in_array($smartFileInfo->getExtension(), $this->allowedFileExtensions, true);
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return $this->allowedFileExtensions;
    }

    public function configure(array $configuration): void
    {
        $this->allowedFileExtensions = $configuration[self::ALLOWED_FILE_EXTENSIONS] ?? [];
    }

    private function processFile(File $file): void
    {
        try {
            $this->currentFileProvider->setFile($file);

            $smartFileInfo = $file->getSmartFileInfo();
            $originalStatements = $this->typoscriptParser->parseString($smartFileInfo->getContents());

            $traverser = new Traverser($originalStatements);
            foreach ($this->visitors as $visitor) {
                $traverser->addVisitor($visitor);
            }
            $traverser->walk();

            $editorConfiguration = $this->editorConfigParser->extractConfigurationForFile($smartFileInfo);

            $prettyPrinterConfiguration = PrettyPrinterConfiguration::create();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withEmptyLineBreaks();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withClosingGlobalStatement();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withSpaceIndentation(
                $editorConfiguration->getIndentSize()
            );

            if ($editorConfiguration->getIsTab()) {
                $prettyPrinterConfiguration = $prettyPrinterConfiguration->withTabs();
            }

            $this->typoscriptPrinter->setPrettyPrinterConfiguration($prettyPrinterConfiguration);

            $this->typoscriptPrinter->printStatements($originalStatements, $this->output);

            $typoScriptContent = rtrim($this->output->fetch()) . $editorConfiguration->getEndOfLine();

            $file->changeFileContent($typoScriptContent);
        } catch (TokenizerException $tokenizerException) {
            return;
        }
    }
}
