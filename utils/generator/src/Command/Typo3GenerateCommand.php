<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Command;

use Rector\Core\Exception\ShouldNotHappenException;
use RuntimeException;
use Ssch\TYPO3Rector\Generator\FileSystem\ConfigFilesystem;
use Ssch\TYPO3Rector\Generator\Finder\TemplateFinder;
use Ssch\TYPO3Rector\Generator\Generator\FileGenerator;
use Ssch\TYPO3Rector\Generator\ValueObject\Description;
use Ssch\TYPO3Rector\Generator\ValueObject\Name;
use Ssch\TYPO3Rector\Generator\ValueObject\Typo3RectorRecipe;
use Ssch\TYPO3Rector\Generator\ValueObject\Typo3Version;
use Ssch\TYPO3Rector\Generator\ValueObject\Url;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symplify\SmartFileSystem\SmartFileInfo;

final class Typo3GenerateCommand extends Command
{
    /**
     * @var string
     */
    public const RECTOR_FQN_NAME_PATTERN = 'Ssch\TYPO3Rector\Rector\__Major__\__Minor__\__Type__\__Name__';

    protected static $defaultName = 'typo3-generate';

    protected static $defaultDescription = '[DEV] Create a new TYPO3 Rector, in a proper location, with new tests';

    public function __construct(
        private readonly TemplateFinder $templateFinder,
        private readonly FileGenerator $fileGenerator,
        private readonly OutputInterface $outputStyle,
        private readonly ConfigFilesystem $configFilesystem
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setAliases(['typo3-create']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        /** @var Typo3Version $typo3Version */
        $typo3Version = $helper->ask($input, $output, $this->askForTypo3Version());
        $changelogUrl = $helper->ask($input, $output, $this->askForChangelogUrl());
        $name = $helper->ask($input, $output, $this->askForName());
        $description = $helper->ask($input, $output, $this->askForDescription());
        $type = $helper->ask($input, $output, $this->askForType());

        $recipe = new Typo3RectorRecipe(
            $typo3Version,
            $changelogUrl,
            $name,
            Description::createFromString($description),
            $type
        );

        $templateFileInfos = $this->templateFinder->find($type);

        $templateVariables = [
            '__Major__' => $recipe->getMajorVersion(),
            '__Minor__' => $recipe->getMinorVersion(),
            '__Type__' => $type,
            '__Name__' => $recipe->getRectorName(),
            '__Test_Directory__' => $recipe->getTestDirectory(),
            '__Changelog_Url__' => $recipe->getChangelogUrl(),
            '__Description__' => addslashes($recipe->getDescription()),
        ];

        $targetDirectory = getcwd();

        $generatedFilePaths = $this->fileGenerator->generateFiles(
            $templateFileInfos,
            $templateVariables,
            (string) $targetDirectory
        );

        $testCaseDirectoryPath = $this->resolveTestCaseDirectoryPath($generatedFilePaths);

        $this->configFilesystem->addRuleToConfigurationFile(
            $recipe->getSet(),
            $templateVariables,
            self::RECTOR_FQN_NAME_PATTERN
        );

        $this->printSuccess($recipe->getRectorName(), $generatedFilePaths, $testCaseDirectoryPath);

        return Command::SUCCESS;
    }

    private function askForChangelogUrl(): Question
    {
        $whatIsTheUrlToChangelog = new Question(
            'Url to changelog (i.e. https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/...): '
        );
        $whatIsTheUrlToChangelog->setNormalizer(fn ($url) => Url::createFromString(trim((string) $url)));

        return $whatIsTheUrlToChangelog;
    }

    private function askForTypo3Version(): Question
    {
        $whatTypo3Version = new Question('TYPO3-Version (i.e. 12.0): ');
        $whatTypo3Version->setNormalizer(fn ($version) => Typo3Version::createFromString(trim((string) $version)));

        return $whatTypo3Version;
    }

    private function askForName(): Question
    {
        $giveMeYourName = new Question('Name (i.e MigrateRequiredFlag): ');
        $giveMeYourName->setNormalizer(fn ($name) => Name::createFromString(trim((string) $name)));

        return $giveMeYourName;
    }

    private function askForDescription(): Question
    {
        $description = new Question('Description (i.e. Migrate required flag): ');
        $description->setValidator(function ($description) {
            if (! is_string($description)) {
                throw new RuntimeException('The description must not be empty');
            }

            return $description;
        });

        return $description;
    }

    private function askForType(): Question
    {
        $question = new ChoiceQuestion('Please select the rector type (defaults to typo3)', [
            'typo3',
            'tca',
            'flexform',
            'typoscript',
        ], 0);
        $question->setErrorMessage('Type %s is invalid.');

        return $question;
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function printSuccess(string $name, array $generatedFilePaths, string $testCaseFilePath): void
    {
        $message = sprintf('New files generated for "%s":', $name);
        $this->outputStyle->writeln($message);

        sort($generatedFilePaths);

        foreach ($generatedFilePaths as $generatedFilePath) {
            $fileInfo = new SmartFileInfo($generatedFilePath);
            $relativeFilePath = $fileInfo->getRelativeFilePathFromCwd();
            $this->outputStyle->writeln(' * ' . $relativeFilePath);
        }

        $message = sprintf('Make tests green again:%svendor/bin/phpunit %s', PHP_EOL . PHP_EOL, $testCaseFilePath);

        $this->outputStyle->writeln($message);
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function resolveTestCaseDirectoryPath(array $generatedFilePaths): string
    {
        foreach ($generatedFilePaths as $generatedFilePath) {
            if (! \str_ends_with($generatedFilePath, 'Test.php')) {
                continue;
            }

            $generatedFileInfo = new SmartFileInfo($generatedFilePath);
            return dirname($generatedFileInfo->getRelativeFilePathFromCwd());
        }

        throw new ShouldNotHappenException();
    }
}
