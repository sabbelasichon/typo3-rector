<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\Output;

use Nette\Utils\Strings;
use Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser;
use Rector\ChangesReporting\Application\ErrorAndDiffCollector;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Configuration\Option;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\Core\ValueObject\Application\RectorError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\NodeTypeResolver\Node\AttributeKey;
use ReflectionClass;
use ReflectionException;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\SmartFileInfo;

final class DecoratedConsoleOutputFormatter implements OutputFormatterInterface
{
    /**
     * @var string
     * @see https://regex101.com/r/q8I66g/1
     */
    private const ON_LINE_REGEX = '# on line #';

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var OutputFormatterInterface
     */
    private $consoleOutputFormatter;

    /**
     * @var BetterPhpDocParser
     */
    private $betterPhpDocParser;

    /**
     * @var ParameterProvider
     */
    private $parameterProvider;

    public function __construct(
        OutputFormatterInterface $consoleOutputFormatter,
        BetterStandardPrinter $betterStandardPrinter,
        Configuration $configuration,
        SymfonyStyle $symfonyStyle,
        BetterPhpDocParser $betterPhpDocParser,
        ParameterProvider $parameterProvider
    ) {
        $this->symfonyStyle = $symfonyStyle;
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->configuration = $configuration;
        $this->consoleOutputFormatter = $consoleOutputFormatter;
        $this->betterPhpDocParser = $betterPhpDocParser;
        $this->parameterProvider = $parameterProvider;
    }

    public function report(ErrorAndDiffCollector $errorAndDiffCollector): void
    {
        if (false === (bool) $this->parameterProvider->provideParameter(Typo3Option::OUTPUT_CHANGELOG)) {
            $this->consoleOutputFormatter->report($errorAndDiffCollector);
            return;
        }

        if ($this->configuration->getOutputFile()) {
            $message = sprintf(
                'Option "--%s" can be used only with "--%s %s"',
                Option::OPTION_OUTPUT_FILE,
                Option::OPTION_OUTPUT_FORMAT,
                'json'
            );
            $this->symfonyStyle->error($message);
        }

        $this->reportFileDiffs($errorAndDiffCollector->getFileDiffs());
        $this->reportErrors($errorAndDiffCollector->getErrors());
        $this->reportRemovedFilesAndNodes($errorAndDiffCollector);

        if ([] !== $errorAndDiffCollector->getErrors()) {
            return;
        }

        $changeCount = $errorAndDiffCollector->getFileDiffsCount()
                       + $errorAndDiffCollector->getRemovedAndAddedFilesCount();
        $message = 'Rector is done!';
        if ($changeCount > 0) {
            $message .= sprintf(
                ' %d file%s %s.',
                $changeCount,
                $changeCount > 1 ? 's' : '',
                $this->configuration->isDryRun() ? 'would have changed (dry-run)' : (1 === $changeCount ? 'has' : 'have') . ' been changed'
            );
        }

        $this->symfonyStyle->success($message);
    }

    public function getName(): string
    {
        return $this->consoleOutputFormatter->getName();
    }

    /**
     * @param FileDiff[] $fileDiffs
     */
    private function reportFileDiffs(array $fileDiffs): void
    {
        if (count($fileDiffs) <= 0) {
            return;
        }

        // normalize
        ksort($fileDiffs);
        $message = sprintf('%d file%s with changes', count($fileDiffs), 1 === count($fileDiffs) ? '' : 's');

        $this->symfonyStyle->title($message);

        $i = 0;
        foreach ($fileDiffs as $fileDiff) {
            $relativeFilePath = $fileDiff->getRelativeFilePath();
            $message = sprintf('<options=bold>%d) %s</>', ++$i, $relativeFilePath);

            $this->symfonyStyle->writeln($message);
            $this->symfonyStyle->newLine();
            $this->symfonyStyle->writeln($fileDiff->getDiffConsoleFormatted());
            $this->symfonyStyle->newLine();

            if ([] !== $fileDiff->getRectorChanges()) {
                $this->symfonyStyle->writeln('<options=underscore>Applied rules:</>');
                $this->symfonyStyle->newLine();

                $appliedRules = [];
                foreach ($fileDiff->getRectorClasses() as $rectorClass) {
                    $appliedRuleKey = md5($rectorClass);
                    $appliedRules[$appliedRuleKey] = $rectorClass;

                    if (! class_exists($rectorClass)) {
                        continue;
                    }

                    try {
                        $rectorReflection = new ReflectionClass($rectorClass);
                        if (! is_string($rectorReflection->getDocComment())) {
                            continue;
                        }
                        $phpDocNode = $this->betterPhpDocParser->parseString($rectorReflection->getDocComment());
                        $seeTags = $phpDocNode->getTagsByName('@see');

                        if (count($seeTags) > 0) {
                            $appliedRules[$appliedRuleKey] = sprintf('%s (%s)', $rectorClass, $seeTags[0]->value);
                        }
                    } catch (ReflectionException $reflectionException) {
                    }
                }
                $this->symfonyStyle->listing($appliedRules);

                $this->symfonyStyle->newLine();
            }
        }
    }

    /**
     * @param RectorError[] $errors
     */
    private function reportErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $errorMessage = $error->getMessage();
            $errorMessage = $this->normalizePathsToRelativeWithLine($errorMessage);

            $message = sprintf(
                'Could not process "%s" file%s, due to: %s"%s".',
                $error->getFileInfo()
                    ->getRelativeFilePathFromCwd(),
                $error->getRectorClass() ? ' by "' . $error->getRectorClass() . '"' : '',
                PHP_EOL,
                $errorMessage
            );

            if ($error->getLine()) {
                $message .= ' On line: ' . $error->getLine();
            }

            $this->symfonyStyle->error($message);
        }
    }

    private function reportRemovedFilesAndNodes(ErrorAndDiffCollector $errorAndDiffCollector): void
    {
        if (0 !== $errorAndDiffCollector->getAddFilesCount()) {
            $message = sprintf('%d files were added', $errorAndDiffCollector->getAddFilesCount());
            $this->symfonyStyle->note($message);
        }

        if (0 !== $errorAndDiffCollector->getRemovedFilesCount()) {
            $message = sprintf('%d files were removed', $errorAndDiffCollector->getRemovedFilesCount());
            $this->symfonyStyle->note($message);
        }

        $this->reportRemovedNodes($errorAndDiffCollector);
    }

    private function normalizePathsToRelativeWithLine(string $errorMessage): string
    {
        $errorMessage = Strings::replace($errorMessage, '#' . preg_quote(getcwd(), '#') . '/#');

        return Strings::replace($errorMessage, self::ON_LINE_REGEX, ':');
    }

    private function reportRemovedNodes(ErrorAndDiffCollector $errorAndDiffCollector): void
    {
        if (0 === $errorAndDiffCollector->getRemovedNodeCount()) {
            return;
        }

        $message = sprintf('%d nodes were removed', $errorAndDiffCollector->getRemovedNodeCount());

        $this->symfonyStyle->warning($message);

        if ($this->symfonyStyle->isVeryVerbose()) {
            $i = 0;
            foreach ($errorAndDiffCollector->getRemovedNodes() as $removedNode) {
                /** @var SmartFileInfo $fileInfo */
                $fileInfo = $removedNode->getAttribute(AttributeKey::FILE_INFO);
                $message = sprintf(
                    '<options=bold>%d) %s:%d</>',
                    ++$i,
                    $fileInfo->getRelativeFilePath(),
                    $removedNode->getStartLine()
                );

                $this->symfonyStyle->writeln($message);

                $printedNode = $this->betterStandardPrinter->print($removedNode);

                // color red + prefix with "-" to visually demonstrate removal
                $printedNode = '-' . Strings::replace($printedNode, '#\n#', "\n-");
                $printedNode = $this->colorTextToRed($printedNode);

                $this->symfonyStyle->writeln($printedNode);
                $this->symfonyStyle->newLine(1);
            }
        }
    }

    private function colorTextToRed(string $text): string
    {
        return '<fg=red>' . $text . '</fg=red>';
    }
}
