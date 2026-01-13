<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Command;

use Rector\Exception\ShouldNotHappenException;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\Generator\Factory\Typo3RectorTypeFactory;
use Ssch\TYPO3Rector\Generator\FileSystem\ConfigFilesystemWriter;
use Ssch\TYPO3Rector\Generator\Finder\TemplateFinder;
use Ssch\TYPO3Rector\Generator\Generator\FileGenerator;
use Ssch\TYPO3Rector\Generator\ValueObject\CodeQualityRectorRecipe;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Webmozart\Assert\Assert;

final class GenerateCodeQualityRuleCommand extends Command
{
    /**
     * @var string
     */
    private const RECTOR_FQN_NAME_PATTERN = 'Ssch\TYPO3Rector\CodeQuality\General\__Name__';

    /**
     * @readonly
     */
    private TemplateFinder $templateFinder;

    /**
     * @readonly
     */
    private FileGenerator $fileGenerator;

    /**
     * @readonly
     */
    private OutputInterface $outputStyle;

    /**
     * @readonly
     */
    private ConfigFilesystemWriter $configFilesystemWriter;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(
        TemplateFinder $templateFinder,
        FileGenerator $fileGenerator,
        OutputInterface $outputStyle,
        ConfigFilesystemWriter $configFilesystemWriter,
        FileInfoFactory $fileInfoFactory
    ) {
        parent::__construct();
        $this->templateFinder = $templateFinder;
        $this->fileGenerator = $fileGenerator;
        $this->outputStyle = $outputStyle;
        $this->configFilesystemWriter = $configFilesystemWriter;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    protected function configure(): void
    {
        $this->setName('generate-code-quality-rule');
        $this->setDescription('Create a new code quality Rector rule, in a proper location, with tests');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $name = $helper->ask($input, $output, $this->askForName());
        $description = $helper->ask($input, $output, $this->askForDescription());
        $type = $helper->ask($input, $output, $this->askForType());

        $recipe = new CodeQualityRectorRecipe($name, $description, Typo3RectorTypeFactory::fromString($type));

        $templateFileInfos = $this->templateFinder->find();

        $templateVariables = [
            '__MajorPrefixed__' => 'CodeQuality',
            '__Major__' => 'CodeQuality',
            '__MinorPrefixed__' => 'General',
            '__Name__' => $recipe->getRectorName(),
            '__Test_Directory__' => $recipe->getTestDirectory(),
            '__Changelog_Url__' => '',
            '__Description__' => addslashes($recipe->getDescription()),
            '__Base_Rector_Class__' => $recipe->getRectorClass(),
            '__Base_Rector_ShortClassName__' => $recipe->getRectorShortClassName(),
            '__Base_Rector_Body_Template__' => $recipe->getRectorBodyTemplate(),
        ];

        $targetDirectory = getcwd();

        $generatedFilePaths = $this->fileGenerator->generateFiles(
            $templateFileInfos,
            $templateVariables,
            (string) $targetDirectory
        );

        $this->configFilesystemWriter->addRuleToConfigurationFile(
            $recipe->getSet(),
            $templateVariables,
            self::RECTOR_FQN_NAME_PATTERN
        );

        $testCaseDirectoryPath = $this->resolveTestCaseDirectoryPath($generatedFilePaths);
        $this->printSuccess($recipe->getRectorName(), $generatedFilePaths, $testCaseDirectoryPath);

        return Command::SUCCESS;
    }

    private function askForName(): Question
    {
        $giveMeYourName = new Question('Name (i.e MigrateRequiredFlag): ');
        $giveMeYourName->setNormalizer(static fn ($name) => preg_replace('/Rector$/', '', ucfirst((string) $name)));
        $giveMeYourName->setMaxAttempts(3);
        $giveMeYourName->setValidator(static function (string $name) {
            Assert::minLength($name, 5);
            Assert::maxLength($name, 80);
            Assert::notContains($name, ' ', 'The name must not contain spaces');
            // Pattern from: https://www.php.net/manual/en/language.oop5.basic.php
            Assert::regex(
                $name,
                '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/',
                'The name must be a valid PHP class name. A valid class name starts with a letter or underscore, followed by any number of letters, numbers, or underscores.'
            );

            return $name;
        });

        return $giveMeYourName;
    }

    private function askForDescription(): Question
    {
        $description = new Question('Description (i.e. Migrate required flag): ');
        $description->setMaxAttempts(3);
        $description->setValidator(static function (?string $description) {
            Assert::notNull($description, 'Please enter a description');
            Assert::minLength($description, 5);
            Assert::maxLength($description, 120);

            return $description;
        });

        return $description;
    }

    private function askForType(): Question
    {
        $question = new ChoiceQuestion('Please select the rector type (defaults to typo3)', ['typo3', 'tca'], 'typo3');
        $question->setMaxAttempts(3);
        $question->setErrorMessage('Type %s is invalid.');

        return $question;
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function printSuccess(string $name, array $generatedFilePaths, string $testCaseFilePath): void
    {
        $message = sprintf('<info>New files generated for "%s":</info>', $name);
        $this->outputStyle->writeln($message);

        sort($generatedFilePaths);

        foreach ($generatedFilePaths as $generatedFilePath) {
            $fileInfo = $this->fileInfoFactory->createFileInfoFromPath($generatedFilePath);
            $this->outputStyle->writeln(' * ' . $fileInfo->getRelativePathname());
        }

        $message = sprintf(
            '<info>Run tests for this rector:</info>%svendor/bin/phpunit %s',
            PHP_EOL . PHP_EOL,
            $testCaseFilePath . PHP_EOL
        );
        $this->outputStyle->writeln($message);
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function resolveTestCaseDirectoryPath(array $generatedFilePaths): string
    {
        foreach ($generatedFilePaths as $generatedFilePath) {
            if (! \str_ends_with($generatedFilePath, 'Test.php')
                && ! \str_ends_with($generatedFilePath, 'Test.php.inc')
            ) {
                continue;
            }

            $generatedFileInfo = $this->fileInfoFactory->createFileInfoFromPath($generatedFilePath);
            return $generatedFileInfo->getRelativePath();
        }

        throw new ShouldNotHappenException();
    }
}
