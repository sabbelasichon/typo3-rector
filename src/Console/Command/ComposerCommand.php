<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\Command;

use Nette\Utils\JsonException;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\Application\ErrorAndDiffCollector;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Autoloading\AdditionalAutoloader;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Configuration\Option;
use Rector\Core\Console\Command\AbstractCommand;
use Rector\Core\Console\Output\OutputFormatterCollector;
use Rector\Core\FileSystem\FilesFinder;
use Ssch\TYPO3Rector\Composer\ComposerProcessor;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\ShellCode;

final class ComposerCommand extends AbstractCommand
{
    /**
     * @var FilesFinder
     */
    private $filesFinder;

    /**
     * @var AdditionalAutoloader
     */
    private $additionalAutoloader;

    /**
     * @var ErrorAndDiffCollector
     */
    private $errorAndDiffCollector;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var OutputFormatterCollector
     */
    private $outputFormatterCollector;

    /**
     * @var ComposerProcessor
     */
    private $composerProcessor;

    public function __construct(
        AdditionalAutoloader $additionalAutoloader,
        ChangedFilesDetector $changedFilesDetector,
        Configuration $configuration,
        ErrorAndDiffCollector $errorAndDiffCollector,
        FilesFinder $phpFilesFinder,
        OutputFormatterCollector $outputFormatterCollector,
        ComposerProcessor $composerProcessor
    ) {
        $this->filesFinder = $phpFilesFinder;
        $this->additionalAutoloader = $additionalAutoloader;
        $this->errorAndDiffCollector = $errorAndDiffCollector;
        $this->configuration = $configuration;
        $this->outputFormatterCollector = $outputFormatterCollector;
        $this->changedFilesDetector = $changedFilesDetector;
        $this->composerProcessor = $composerProcessor;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setAliases(['composer']);

        $this->setDescription('Upgrade custom composer.json');
        $this->addArgument(
            Option::SOURCE,
            InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
            'Files or directories to be upgraded.'
        );
        $this->addOption(
            Option::OPTION_DRY_RUN,
            'n',
            InputOption::VALUE_NONE,
            'See diff of changes, do not save them to files.'
        );

        $names = $this->outputFormatterCollector->getNames();

        $description = sprintf('Select output format: "%s".', implode('", "', $names));
        $this->addOption(
            Option::OPTION_OUTPUT_FORMAT,
            'o',
            InputOption::VALUE_OPTIONAL,
            $description,
            ConsoleOutputFormatter::NAME
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->configuration->setIsDryRun((bool) $input->getOption(Option::OPTION_DRY_RUN));
        $paths = $this->configuration->getPaths();
        $commandLinePaths = (array) $input->getArgument(Option::SOURCE);
        // manual command line value has priority
        if ([] !== $commandLinePaths) {
            $paths = $commandLinePaths;
        }

        $this->additionalAutoloader->autoloadWithInputAndSource($input, $paths);

        $composerJsonFiles = $this->filesFinder->findInDirectoriesAndFiles($paths, ['json']);

        foreach ($composerJsonFiles as $composerJsonFile) {
            try {
                $this->composerProcessor->process($composerJsonFile->getRealPath());
            } catch (JsonException $jsonException) {
            }
        }

        $outputFormatOption = $input->getOption(Option::OPTION_OUTPUT_FORMAT);

        if (is_array($outputFormatOption)) {
            $outputFormatOption = ConsoleOutputFormatter::NAME;
        }
        // report diffs and errors
        $outputFormat = (string) $outputFormatOption;

        $outputFormatter = $this->outputFormatterCollector->getByName($outputFormat);
        $outputFormatter->report($this->errorAndDiffCollector);

        // inverse error code for CI dry-run
        if ($this->configuration->isDryRun() && $this->errorAndDiffCollector->getFileDiffsCount()) {
            return ShellCode::ERROR;
        }

        return ShellCode::SUCCESS;
    }
}
