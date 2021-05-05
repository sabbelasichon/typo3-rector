<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Composer;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\PhpParser\Parser\Parser;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Reporting\Reporter;
use Ssch\TYPO3Rector\Reporting\ValueObject\Report;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\ComposerJsonManipulator\Printer\ComposerJsonPrinter;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @see \Ssch\TYPO3Rector\Tests\Composer\InitializeExtensionComposerJsonProcessor\InitializeExtensionComposerJsonProcessorTest
 */
final class InitializeExtensionComposerJsonProcessor implements FileProcessorInterface, RectorInterface
{
    /**
     * @var string
     */
    private const AUTHORS = 'authors';

    /**
     * @var string
     */
    private const DESCRIPTION = 'description';

    /**
     * @var string
     */
    private const VERSION = 'version';

    /**
     * @var string
     */
    private const AUTHOR = 'author';

    /**
     * @var string
     */
    private const AUTHOR_EMAIL = 'author_email';

    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var ComposerJsonFactory
     */
    private $composerJsonFactory;

    /**
     * @var ComposerJsonPrinter
     */
    private $composerJsonPrinter;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Reporter
     */
    private $reporter;

    public function __construct(
        SmartFileSystem $smartFileSystem,
        ComposerJsonFactory $composerJsonFactory,
        ComposerJsonPrinter $composerJsonPrinter,
        Parser $parser,
        Configuration $configuration,
        Reporter $reporter
    ) {
        $this->smartFileSystem = $smartFileSystem;
        $this->composerJsonFactory = $composerJsonFactory;
        $this->composerJsonPrinter = $composerJsonPrinter;
        $this->parser = $parser;
        $this->configuration = $configuration;
        $this->reporter = $reporter;
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();
        if ('ext_emconf.php' !== $smartFileInfo->getBasename()) {
            return false;
        }

        return ! $this->extensionComposerJsonExists($smartFileInfo);
    }

    public function process(array $files): void
    {
        foreach ($files as $file) {
            $smartFileInfo = $file->getSmartFileInfo();

            $composerJson = $this->createComposerJson($smartFileInfo);

            $information = $this->readExtEmConf($smartFileInfo);

            // Can be read from ext_emconf.php
            $composerJson->setRequire([
                'typo3/cms-core' => '*',
            ]);
            $composerJson->setAuthors([$information[self::AUTHORS]]);
            $composerJson->setDescription((string) $information[self::DESCRIPTION]);
            $composerJson->setVersion((string) $information[self::VERSION]);

            $composerJsonFilePath = $this->createComposerJsonFilePath($smartFileInfo);

            $json = $this->composerJsonPrinter->printToString($composerJson);
            $report = new Report(sprintf(
                'Create new composer.json "%s" with content "%s"',
                $composerJsonFilePath,
                $json
            ), $this);
            $this->reporter->report($report);

            if (! $this->configuration->isDryRun()) {
                $this->composerJsonPrinter->print($composerJson, $composerJsonFilePath);
            }
        }
    }

    public function getSupportedFileExtensions(): array
    {
        return ['php'];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Create new composer.json file for extension',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
{
    "name": "vendor/test",
    "description": "Some description",
    "license": "GPL-2.0-or-later",
    "type": "typo3-cms-extension",
    "authors": [
        {
            "name": "",
            "email": "no-email@given.com"
        }
    ],
    "extra": {
        "typo3/cms": {
            "extension-key": "test"
        }
    },
    "version": "dev-local",
    "autoload": {
        "classmap": [
            "*"
        ]
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    private function extensionComposerJsonExists(SmartFileInfo $smartFileInfo): bool
    {
        return $this->smartFileSystem->exists($this->createComposerJsonFilePath($smartFileInfo));
    }

    private function createComposerJsonFilePath(SmartFileInfo $smartFileInfo): string
    {
        $extensionDirectory = $smartFileInfo->getRealPathDirectory();

        return sprintf('%s/composer.json', $extensionDirectory);
    }

    private function createComposerJson(SmartFileInfo $smartFileInfo): ComposerJson
    {
        $composerJson = $this->composerJsonFactory->createEmpty();

        $extensionKey = basename($smartFileInfo->getRealPathDirectory());
        $composerJson->setName('vendor/' . str_replace('_', '-', $extensionKey));
        $composerJson->setType('typo3-cms-extension');
        $composerJson->setLicense('GPL-2.0-or-later');
        $composerJson->setExtra([
            'typo3/cms' => [
                'extension-key' => $extensionKey,
            ],
        ]);
        $composerJson->setAutoload([
            'classmap' => ['*'],
        ]);

        return $composerJson;
    }

    /**
     * @return array<string, mixed>
     */
    private function readExtEmConf(SmartFileInfo $smartFileInfo): array
    {
        $nodes = $this->parser->parseFileInfo($smartFileInfo);

        $information = [
            self::DESCRIPTION => 'Add Description here',
            self::AUTHORS => [],
            self::VERSION => null,
        ];

        foreach ($nodes as $node) {
            if (! $node instanceof Expression) {
                continue;
            }

            if (! $node->expr instanceof Assign) {
                continue;
            }

            if (! $node->expr->var instanceof ArrayDimFetch) {
                continue;
            }

            if (! $node->expr->expr instanceof Array_) {
                continue;
            }

            if ([] === $node->expr->expr->items || null === $node->expr->expr->items) {
                continue;
            }

            foreach ($node->expr->expr->items as $item) {
                /** @var ArrayItem $item */
                if (! $item->key instanceof String_) {
                    continue;
                }

                if (! in_array(
                    $item->key->value,
                    [self::DESCRIPTION, self::VERSION, self::AUTHOR, self::AUTHOR_EMAIL],
                    true
                )) {
                    continue;
                }

                if (! $item->value instanceof String_) {
                    continue;
                }

                if (null === $item->value->value) {
                    continue;
                }

                $information[$item->key->value] = $item->value->value;
            }
        }

        if (array_key_exists(self::AUTHOR, $information) && array_key_exists(self::AUTHOR_EMAIL, $information)) {
            $information[self::AUTHORS] = [
                'name' => $information[self::AUTHOR],
                'email' => $information[self::AUTHOR_EMAIL],
            ];
        }

        return $information;
    }
}
