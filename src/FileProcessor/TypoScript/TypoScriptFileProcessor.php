<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript;

use Helmich\TypoScriptParser\Parser\AST\NestedAssignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Helmich\TypoScriptParser\Parser\ParseError;
use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinterConfiguration;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Helmich\TypoScriptParser\Tokenizer\TokenizerException;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Parameter\ParameterProvider;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Parallel\ValueObject\Bridge;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\ConvertToPhpFileInterface;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\TypoScriptPostRectorInterface;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\TypoScriptRectorInterface;
use Ssch\TYPO3Rector\Contract\Processor\ConfigurableProcessorInterface;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Collector\RemoveTypoScriptStatementCollector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\ValueObject\Indent;
use Symfony\Component\Console\Output\BufferedOutput;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\TypoScriptProcessorTest
 */
final class TypoScriptFileProcessor implements ConfigurableProcessorInterface
{
    /**
     * @var string
     */
    public const ALLOWED_FILE_EXTENSIONS = 'allowed_file_extensions';

    /**
     * @var string[]
     */
    private array $allowedFileExtensions = ['typoscript', 'ts', 'txt'];

    /**
     * @var FileDiff[]
     */
    private array $fileDiffs = [];

    /**
     * @readonly
     */
    private ParserInterface $typoscriptParser;

    /**
     * @readonly
     */
    private BufferedOutput $output;

    /**
     * @readonly
     */
    private ASTPrinterInterface $typoscriptPrinter;

    /**
     * @readonly
     */
    private CurrentFileProvider $currentFileProvider;

    /**
     * @readonly
     */
    private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;

    /**
     * @readonly
     */
    private RectorOutputStyle $rectorOutputStyle;

    /**
     * @readonly
     */
    private FileDiffFactory $fileDiffFactory;

    /**
     * @readonly
     */
    private RemoveTypoScriptStatementCollector $removeTypoScriptStatementCollector;

    /**
     * @var TypoScriptRectorInterface[]
     * @readonly
     */
    private iterable $typoScriptRectors = [];

    /**
     * @var TypoScriptPostRectorInterface[]
     * @readonly
     */
    private iterable $typoScriptPostRectors = [];

    private ParameterProvider $parameterProvider;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    /**
     * @param TypoScriptRectorInterface[] $typoScriptRectors
     * @param TypoScriptPostRectorInterface[] $typoScriptPostRectors
     */
    public function __construct(
        ParserInterface $typoscriptParser,
        BufferedOutput $output,
        ASTPrinterInterface $typoscriptPrinter,
        CurrentFileProvider $currentFileProvider,
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        RectorOutputStyle $rectorOutputStyle,
        FileDiffFactory $fileDiffFactory,
        RemoveTypoScriptStatementCollector $removeTypoScriptStatementCollector,
        ParameterProvider $parameterProvider,
        FileInfoFactory $fileInfoFactory,
        iterable $typoScriptRectors = [],
        iterable $typoScriptPostRectors = []
    ) {
        $this->typoscriptParser = $typoscriptParser;
        $this->output = $output;
        $this->typoscriptPrinter = $typoscriptPrinter;
        $this->currentFileProvider = $currentFileProvider;
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->rectorOutputStyle = $rectorOutputStyle;
        $this->fileDiffFactory = $fileDiffFactory;
        $this->removeTypoScriptStatementCollector = $removeTypoScriptStatementCollector;
        $this->typoScriptRectors = $typoScriptRectors;
        $this->typoScriptPostRectors = $typoScriptPostRectors;
        $this->parameterProvider = $parameterProvider;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        if ($this->typoScriptRectors === []) {
            return false;
        }

        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        return in_array($smartFileInfo->getExtension(), $this->allowedFileExtensions, true);
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function process(File $file, Configuration $configuration): array
    {
        $this->processFile($file);
        $this->convertTypoScriptToPhpFiles();

        return [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => $this->fileDiffs,
        ];
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return $this->allowedFileExtensions;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $allowedFileExtensions = $configuration[self::ALLOWED_FILE_EXTENSIONS] ?? $configuration;
        Assert::isArray($allowedFileExtensions);
        Assert::allString($allowedFileExtensions);

        $this->allowedFileExtensions = $allowedFileExtensions;
    }

    private function processFile(File $file): void
    {
        try {
            $this->currentFileProvider->setFile($file);

            $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());
            $originalStatements = $this->typoscriptParser->parseString($smartFileInfo->getContents());

            $traverser = new Traverser($originalStatements);
            foreach ($this->typoScriptRectors as $visitor) {
                $traverser->addVisitor($visitor);
            }

            $traverser->walk();

            $typoScriptRectors = is_array($this->typoScriptRectors) ? $this->typoScriptRectors : iterator_to_array(
                $this->typoScriptRectors
            );

            $typoscriptRectorsWithChange = array_filter(
                $typoScriptRectors,
                static fn (AbstractTypoScriptRector $typoScriptRector) => $typoScriptRector->hasChanged()
            );

            if ($typoscriptRectorsWithChange === []) {
                return;
            }

            // keep original TypoScript format
            $indent = Indent::fromFile($file);

            $prettyPrinterConfiguration = PrettyPrinterConfiguration::create();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withEmptyLineBreaks();

            if ($indent->isSpace()) {
                // default indent
                $indentation = $this->parameterProvider->provideParameter(
                    Typo3Option::TYPOSCRIPT_INDENT_SIZE
                ) ?? $indent->length();
                $prettyPrinterConfiguration = $prettyPrinterConfiguration->withSpaceIndentation($indentation);
            } else {
                $prettyPrinterConfiguration = $prettyPrinterConfiguration->withTabs();
            }

            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withClosingGlobalStatement();
            $this->typoscriptPrinter->setPrettyPrinterConfiguration($prettyPrinterConfiguration);

            $printStatements = $this->filterRemovedStatements($originalStatements, $file);
            $this->typoscriptPrinter->printStatements($printStatements, $this->output);

            $newTypoScriptContent = $this->applyTypoScriptPostRectors($this->output->fetch());
            $typoScriptContent = rtrim($newTypoScriptContent) . "\n";

            $oldFileContents = $file->getFileContent();

            $file->changeFileContent($typoScriptContent);

            $this->fileDiffs[] = $this->fileDiffFactory->createFileDiff(
                $file,
                $oldFileContents,
                $file->getFileContent()
            );
        } catch (TokenizerException $tokenizerException) {
            return;
        } catch (ParseError $parseError) {
            $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());
            $errorFile = $smartFileInfo->getRelativePathname();
            $this->rectorOutputStyle->warning(sprintf('TypoScriptParser Error in: %s. File skipped.', $errorFile));
        }
    }

    /**
     * @return ConvertToPhpFileInterface[]
     */
    private function convertToPhpFileRectors(): array
    {
        $typoScriptRectors = is_array($this->typoScriptRectors) ? $this->typoScriptRectors : iterator_to_array(
            $this->typoScriptRectors
        );

        return array_filter(
            $typoScriptRectors,
            static fn (Visitor $visitor): bool => $visitor instanceof ConvertToPhpFileInterface
        );
    }

    private function convertTypoScriptToPhpFiles(): void
    {
        foreach ($this->convertToPhpFileRectors() as $convertToPhpFileVisitor) {
            $addedFileWithContent = $convertToPhpFileVisitor->convert();
            if (! $addedFileWithContent instanceof AddedFileWithContent) {
                continue;
            }

            $this->removedAndAddedFilesCollector->addAddedFile($addedFileWithContent);

            $this->rectorOutputStyle->warning($convertToPhpFileVisitor->getMessage());
        }
    }

    private function applyTypoScriptPostRectors(string $content): string
    {
        foreach ($this->typoScriptPostRectors as $typoScriptPostRector) {
            $content = $typoScriptPostRector->apply($content);
        }

        return $content;
    }

    /**
     * @param Statement[] $originalStatements
     *
     * @return Statement[]
     */
    private function filterRemovedStatements(array $originalStatements, File $file): array
    {
        $printStatements = [];
        foreach ($originalStatements as $originalStatement) {
            if (! $this->removeTypoScriptStatementCollector->shouldStatementBeRemoved($originalStatement, $file)) {
                $printStatements[] = $originalStatement;
            }

            if ($originalStatement instanceof NestedAssignment) {
                $originalStatement->statements = $this->filterRemovedStatements($originalStatement->statements, $file);
            }
        }

        return $printStatements;
    }
}
