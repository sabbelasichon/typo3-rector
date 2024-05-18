<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Rector\FileSystem\InitFilePathsResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

$autoloadPaths = [
    // Package was included as a library
    __DIR__ . '/../../../autoload.php',
    // Local package usage
    __DIR__ . '/../vendor/autoload.php',
    // Local package in packages folder
    __DIR__ . '/../../../vendor/autoload.php',
];

foreach ($autoloadPaths as $path) {
    if (! file_exists($path)) {
        continue;
    }

    include_once $path;
    break;
}

(new SingleCommandApplication())
    ->setName('Initialize TYPO3-Rector configuration')
    ->setVersion('2.6.0')
    ->setDescription('Initializes a bare configuration to start with your TYPO3 upgrade')
    ->setCode(static function (InputInterface $input, OutputInterface $output): int {
        $projectDirectory = getcwd();
        if ($projectDirectory === false) {
            $output->writeln('<error>Could not determine current working directory!</error>');
            return Command::FAILURE;
        }

        $commonRectorConfigPath = $projectDirectory . '/rector.php';
        if (file_exists($commonRectorConfigPath)) {
            $output->writeln('Configuration already exists.');
            return Command::FAILURE;
        }

        $iniFilePathsResolver = new InitFilePathsResolver();
        $projectPhpDirectories = $iniFilePathsResolver->resolve($projectDirectory);
        // fallback to default 'src' in case of empty one
        if ($projectPhpDirectories === []) {
            $projectPhpDirectories[] = 'src';
        }

        // Filter some blacklisted folders
        $projectPhpDirectories = array_filter(
            $projectPhpDirectories,
            static fn (string $projectDirectory) => $projectDirectory !== 'public'
        );
        $projectPhpDirectoriesContents = '';
        foreach ($projectPhpDirectories as $projectPhpDirectory) {
            $projectPhpDirectoriesContents .= "        __DIR__ . '/" . $projectPhpDirectory . "'," . \PHP_EOL;
        }

        $extensionFiles = ['ext_emconf.php', 'ext_localconf.php', 'ext_tables.php'];
        foreach ($extensionFiles as $file) {
            if (file_exists($projectDirectory . '/' . $file)) {
                $projectPhpDirectoriesContents .= "        __DIR__ . '/" . $file . "'," . \PHP_EOL;
            }
        }

        $projectPhpDirectoriesContents = \rtrim($projectPhpDirectoriesContents);

        $configContents = FileSystem::read(__DIR__ . '/../templates/rector.php.dist');
        $configContents = \str_replace('__PATHS__', $projectPhpDirectoriesContents, $configContents);

        $output->writeln(
            '<info>The config is added now. Run "rector process" command to make Rector do the work!</info>'
        );
        FileSystem::write($commonRectorConfigPath, $configContents, null);
        return Command::SUCCESS;
    })
    ->run();
