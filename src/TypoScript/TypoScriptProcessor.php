<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript;

use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinterConfiguration;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Helmich\TypoScriptParser\Tokenizer\TokenizerException;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\EditorConfig\EditorConfigParser;
use Ssch\TYPO3Rector\Processor\ConfigurableProcessorInterface;
use Ssch\TYPO3Rector\ValueObject\EditorConfigConfiguration;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\SmartFileSystem;

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
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @param Visitor[] $visitors
     */
    public function __construct(
        ParserInterface $typoscriptParser,
        BufferedOutput $output,
        ASTPrinterInterface $typoscriptPrinter,
        CurrentFileProvider $currentFileProvider,
        EditorConfigParser $editorConfigParser,
        SmartFileSystem $smartFileSystem,
        Configuration $configuration,
        SymfonyStyle $symfonyStyle,
        array $visitors = []
    ) {
        $this->typoscriptParser = $typoscriptParser;

        $this->typoscriptPrinter = $typoscriptPrinter;
        $this->output = $output;
        $this->visitors = $visitors;
        $this->currentFileProvider = $currentFileProvider;
        $this->editorConfigParser = $editorConfigParser;
        $this->smartFileSystem = $smartFileSystem;
        $this->configuration = $configuration;
        $this->symfonyStyle = $symfonyStyle;
    }

    /**
     * @param File[] $files
     */
    public function process(array $files): void
    {
        foreach ($files as $file) {
            $this->processFile($file);
        }

        $this->convertTypoScriptToPhpFiles();
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

            $defaultEditorConfiguration = new EditorConfigConfiguration(
                EditorConfigConfiguration::SPACE,
                4,
                EditorConfigConfiguration::LINE_FEED
            );
            $editorConfiguration = $this->editorConfigParser->extractConfigurationForFile(
                $smartFileInfo,
                $defaultEditorConfiguration
            );

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

    /**
     * @return ConvertToPhpFileInterface[]
     */
    private function convertToPhpFileVisitors(): array
    {
        return array_filter($this->visitors, function (Visitor $visitor): bool {
            return is_a($visitor, ConvertToPhpFileInterface::class, true);
        });
    }

    private function convertTypoScriptToPhpFiles(): void
    {
        foreach ($this->convertToPhpFileVisitors() as $convertToPhpFileVisitor) {
            $typoScriptToPhpFile = $convertToPhpFileVisitor->convert();
            $filePath = $this->configuration->getMainConfigFilePath() . $typoScriptToPhpFile->getFilename();

            if (! $this->configuration->isDryRun()) {
                $message = sprintf(
                    'Would create file "%s" with content "%s"',
                    $filePath,
                    $typoScriptToPhpFile->getContent()
                );
                $this->symfonyStyle->info($message);
            } else {
                $this->smartFileSystem->dumpFile($filePath, $typoScriptToPhpFile->getContent());
            }
        }
    }
}
