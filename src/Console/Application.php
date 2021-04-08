<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console;

use Composer\XdebugHandler\XdebugHandler;
use Jean85\PrettyVersions;
use OutOfBoundsException;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Configuration\Option;
use Rector\Core\Exception\Configuration\InvalidConfigurationException;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

/**
 * @todo why is this class overloaded? what is different here that can be handled in Rector itself?
 */
final class Application extends SymfonyApplication
{
    /**
     * @var string
     */
    private const NAME = 'TYPO3 Rector';

    /**
     * @var Configuration
     * @noRector
     */
    private $configuration;

    /**
     * @param Command[] $commands
     */
    public function __construct(Configuration $configuration, CommandNaming $commandNaming, array $commands = [])
    {
        try {
            $typo3RectorVersion = PrettyVersions::getVersion('ssch/typo3-rector')->getPrettyVersion();
        } catch (OutOfBoundsException $outOfBoundsException) {
            $typo3RectorVersion = 'Unknown';
        }

        try {
            $rectorVersion = $configuration->getPrettyVersion();
        } catch (OutOfBoundsException $e) {
            $rectorVersion = 'Unknown';
        }

        $version = sprintf('%s with Rector %s', $typo3RectorVersion, $rectorVersion);

        parent::__construct(self::NAME, $version);

        foreach ($commands as $command) {
            $commandName = $commandNaming->resolveFromCommand($command);
            $command->setName($commandName);
        }

        $this->addCommands($commands);

        /** @noRector configuration */
        $this->configuration = $configuration;
    }

    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        // @fixes https://github.com/rectorphp/rector/issues/2205
        $isXdebugAllowed = $input->hasParameterOption('--xdebug');
        if (! $isXdebugAllowed) {
            $xdebugHandler = new XdebugHandler('typo3-rector', '--ansi');
            $xdebugHandler->check();
            unset($xdebugHandler);
        }

        $shouldFollowByNewline = false;

        // switch working dir
        $newWorkDir = $this->getNewWorkingDir($input);
        if ('' !== $newWorkDir) {
            $oldWorkingDir = getcwd();
            chdir($newWorkDir);
            $output->isDebug() && $output->writeln('Changed CWD form ' . $oldWorkingDir . ' to ' . getcwd());
        }

        // skip in this case, since generate content must be clear from meta-info
        if ($this->shouldPrintMetaInformation($input)) {
            $output->writeln($this->getLongVersion());
            $shouldFollowByNewline = true;
        }

        if ($shouldFollowByNewline) {
            $output->write(PHP_EOL);
        }

        return parent::doRun($input, $output);
    }

    protected function getDefaultInputDefinition(): InputDefinition
    {
        $defaultInputDefinition = parent::getDefaultInputDefinition();

        $this->removeUnusedOptions($defaultInputDefinition);
        $this->addCustomOptions($defaultInputDefinition);

        return $defaultInputDefinition;
    }

    private function getNewWorkingDir(InputInterface $input): string
    {
        $workingDir = $input->getParameterOption('--working-dir');
        if (false !== $workingDir && ! is_dir($workingDir)) {
            $errorMessage = sprintf('Invalid working directory specified, "%s" does not exist.', $workingDir);
            throw new InvalidConfigurationException($errorMessage);
        }

        return (string) $workingDir;
    }

    private function shouldPrintMetaInformation(InputInterface $input): bool
    {
        $hasNoArguments = null === $input->getFirstArgument();
        if ($hasNoArguments) {
            return false;
        }

        $hasVersionOption = $input->hasParameterOption('--version');
        if ($hasVersionOption) {
            return false;
        }

        $outputFormat = $input->getParameterOption(['-o', '--output-format']);
        return ConsoleOutputFormatter::NAME === $outputFormat;
    }

    private function removeUnusedOptions(InputDefinition $inputDefinition): void
    {
        $options = $inputDefinition->getOptions();

        unset($options['quiet'], $options['no-interaction']);

        $inputDefinition->setOptions($options);
    }

    private function addCustomOptions(InputDefinition $inputDefinition): void
    {
        $inputDefinition->addOption(new InputOption(
            Option::OPTION_CONFIG,
            'c',
            InputOption::VALUE_REQUIRED,
            'Path to config file',
            $this->getDefaultConfigPath()
        ));

        $inputDefinition->addOption(new InputOption(
            Option::OPTION_DEBUG,
            null,
            InputOption::VALUE_NONE,
            'Enable debug verbosity (-vvv)'
        ));

        $inputDefinition->addOption(new InputOption(
            Option::XDEBUG,
            null,
            InputOption::VALUE_NONE,
            'Allow running xdebug'
        ));

        $inputDefinition->addOption(new InputOption(
            Option::OPTION_CLEAR_CACHE,
            null,
            InputOption::VALUE_NONE,
            'Clear cache'
        ));

        $inputDefinition->addOption(new InputOption(
            'working-dir',
            null,
            InputOption::VALUE_REQUIRED,
            'If specified, use the given directory as working directory.'
        ));
    }

    private function getDefaultConfigPath(): string
    {
        return getcwd() . '/rector.php';
    }
}
