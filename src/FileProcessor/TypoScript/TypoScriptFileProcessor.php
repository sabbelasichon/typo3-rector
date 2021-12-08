<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript;

use Helmich\TypoScriptParser\Parser\ParseError;
use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinterConfiguration;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Helmich\TypoScriptParser\Tokenizer\TokenizerException;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\FileFormatter\EditorConfig\EditorConfigParser;
use Rector\FileFormatter\ValueObject\Indent;
use Rector\FileFormatter\ValueObjectFactory\EditorConfigConfigurationBuilder;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\ConvertToPhpFileInterface;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\TypoScriptRectorInterface;
use Ssch\TYPO3Rector\Contract\Processor\ConfigurableProcessorInterface;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
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
     * @param TypoScriptRectorInterface[] $typoScriptRectors
     */
    public function __construct(
        private ParserInterface $typoscriptParser,
        private BufferedOutput $output,
        private ASTPrinterInterface $typoscriptPrinter,
        private CurrentFileProvider $currentFileProvider,
        private EditorConfigParser $editorConfigParser,
        private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        private RectorOutputStyle $rectorOutputStyle,
        private array $typoScriptRectors = []
    ) {
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        if ([] === $this->typoScriptRectors) {
            return false;
        }

        $smartFileInfo = $file->getSmartFileInfo();

        return in_array($smartFileInfo->getExtension(), $this->allowedFileExtensions, true);
    }

    public function process(File $file, Configuration $configuration): void
    {
        $this->processFile($file);
        $this->convertTypoScriptToPhpFiles();
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
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> assert configure() input
        $allowedFileExtensions = $configuration[self::ALLOWED_FILE_EXTENSIONS] ?? $configuration;
        Assert::isArray($allowedFileExtensions);
        Assert::allString($allowedFileExtensions);

        $this->allowedFileExtensions = $allowedFileExtensions;
<<<<<<< HEAD
=======
        $this->allowedFileExtensions = $configuration[self::ALLOWED_FILE_EXTENSIONS] ?? $configuration;
>>>>>>> make use of configure() method
=======
>>>>>>> assert configure() input
    }

    private function processFile(File $file): void
    {
        try {
            $this->currentFileProvider->setFile($file);

            $smartFileInfo = $file->getSmartFileInfo();
            $originalStatements = $this->typoscriptParser->parseString($smartFileInfo->getContents());

            $traverser = new Traverser($originalStatements);
            foreach ($this->typoScriptRectors as $visitor) {
                $traverser->addVisitor($visitor);
            }

            $traverser->walk();

            $typoscriptRectorsWithChange = array_filter(
                $this->typoScriptRectors,
                fn (AbstractTypoScriptRector $typoScriptRector) => $typoScriptRector->hasChanged()
            );

            if ([] === $typoscriptRectorsWithChange) {
                return;
            }

            $editorConfigConfigurationBuilder = EditorConfigConfigurationBuilder::create();
            $editorConfigConfigurationBuilder->withIndent(Indent::createSpaceWithSize(4));

            $editorConfiguration = $this->editorConfigParser->extractConfigurationForFile(
                $file,
                $editorConfigConfigurationBuilder
            );

            $prettyPrinterConfiguration = PrettyPrinterConfiguration::create();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withEmptyLineBreaks();

            if ('tab' === $editorConfiguration->getIndentStyle()) {
                $prettyPrinterConfiguration = $prettyPrinterConfiguration->withTabs();
            } else {
                $prettyPrinterConfiguration = $prettyPrinterConfiguration->withSpaceIndentation(
                    $editorConfiguration->getIndentSize()
                );
            }

            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withClosingGlobalStatement();

            $this->typoscriptPrinter->setPrettyPrinterConfiguration($prettyPrinterConfiguration);

            $this->typoscriptPrinter->printStatements($originalStatements, $this->output);

            $typoScriptContent = rtrim($this->output->fetch()) . $editorConfiguration->getNewLine();

            $file->changeFileContent($typoScriptContent);
        } catch (TokenizerException) {
            return;
        } catch (ParseError) {
            $smartFileInfo = $file->getSmartFileInfo();
            $errorFile = $smartFileInfo->getRelativeFilePath();
            $this->rectorOutputStyle->warning(sprintf('TypoScriptParser Error in: %s. File skipped.', $errorFile));

            return;
        }
    }

    /**
     * @return ConvertToPhpFileInterface[]
     */
    private function convertToPhpFileRectors(): array
    {
        return array_filter(
            $this->typoScriptRectors,
            fn (Visitor $visitor): bool => is_a($visitor, ConvertToPhpFileInterface::class, true)
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
}
