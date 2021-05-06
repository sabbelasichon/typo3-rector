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
use Rector\FileFormatter\Contract\EditorConfig\EditorConfigParserInterface;
use Rector\FileFormatter\ValueObject\Indent;
use Rector\FileFormatter\ValueObjectFactory\EditorConfigConfigurationBuilder;
use Ssch\TYPO3Rector\Processor\ConfigurableProcessorInterface;
use Ssch\TYPO3Rector\Reporting\Reporter;
use Ssch\TYPO3Rector\TypoScript\Visitors\AbstractVisitor;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\SmartFileInfo;
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
     * @var EditorConfigParserInterface
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
     * @var Reporter
     */
    private $reporter;

    /**
     * @param Visitor[] $visitors
     */
    public function __construct(
        ParserInterface $typoscriptParser,
        BufferedOutput $output,
        ASTPrinterInterface $typoscriptPrinter,
        CurrentFileProvider $currentFileProvider,
        EditorConfigParserInterface $editorConfigParser,
        SmartFileSystem $smartFileSystem,
        Configuration $configuration,
        SymfonyStyle $symfonyStyle,
        Reporter $reporter,
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
        $this->reporter = $reporter;
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

            $visitorsChanged = array_filter($this->visitors, function (AbstractVisitor $visitor) {
                return $visitor->hasChanged();
            });

            if ([] === $visitorsChanged) {
                return;
            }

            $editorConfigConfigurationBuilder = EditorConfigConfigurationBuilder::anEditorConfigConfiguration();
            $editorConfigConfigurationBuilder->withIndent(Indent::createSpaceWithSize(4));

            $editorConfiguration = $this->editorConfigParser->extractConfigurationForFile(
                $file,
                $editorConfigConfigurationBuilder
            );

            $prettyPrinterConfiguration = PrettyPrinterConfiguration::create();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withEmptyLineBreaks();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withClosingGlobalStatement();

            if ('tab' === $editorConfiguration->getIndentStyle()) {
                $prettyPrinterConfiguration = $prettyPrinterConfiguration->withTabs();
            } else {
                $prettyPrinterConfiguration = $prettyPrinterConfiguration->withSpaceIndentation(
                    $editorConfiguration->getIndentSize()
                );
            }

            $this->typoscriptPrinter->setPrettyPrinterConfiguration($prettyPrinterConfiguration);

            $this->typoscriptPrinter->printStatements($originalStatements, $this->output);

            $typoScriptContent = rtrim($this->output->fetch()) . $editorConfiguration->getNewLine();

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

            if (null === $typoScriptToPhpFile) {
                continue;
            }

            $filePath = $typoScriptToPhpFile->getFilename();

            if ($this->configuration->isDryRun()) {
                $message = sprintf(
                    'Would create file "%s" with content "%s"',
                    $filePath,
                    $typoScriptToPhpFile->getContent()
                );
                $this->symfonyStyle->info($message);
            } else {
                $report = $convertToPhpFileVisitor->getReport();

                $this->smartFileSystem->dumpFile($filePath, $typoScriptToPhpFile->getContent());

                $file = new File(new SmartFileInfo($filePath), $typoScriptToPhpFile->getContent());
                $this->currentFileProvider->setFile($file);

                $this->reporter->report($report);
            }
        }
    }
}
