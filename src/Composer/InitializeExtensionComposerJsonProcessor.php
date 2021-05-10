<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Composer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Core\PhpParser\Parser\Parser;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\ComposerJsonManipulator\Printer\ComposerJsonPrinter;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;
use UnexpectedValueException;

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
     * @var string
     */
    private const AUTOLOAD = 'autoload';

    /**
     * @var string
     */
    private const AUTOLOAD_DEV = 'autoload-dev';

    /**
     * @var string
     */
    private const CONSTRAINTS = 'constraints';

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
     * @var ValueResolver
     */
    private $valueResolver;

    /**
     * @var RemovedAndAddedFilesCollector
     */
    private $removedAndAddedFilesCollector;

    public function __construct(
        ComposerJsonFactory $composerJsonFactory,
        ComposerJsonPrinter $composerJsonPrinter,
        Parser $parser,
        ValueResolver $valueResolver,
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector
    ) {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->composerJsonPrinter = $composerJsonPrinter;
        $this->parser = $parser;
        $this->valueResolver = $valueResolver;
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
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

            $composerJson->setAuthors([$information[self::AUTHORS]]);
            $composerJson->setDescription((string) $information[self::DESCRIPTION]);
            $composerJson->setVersion((string) $information[self::VERSION]);
            $composerJson->setAutoload($information[self::AUTOLOAD]);
            $composerJson->setAutoloadDev($information[self::AUTOLOAD_DEV]);
            $composerJson->setRequire($information['require']);
            $composerJson->setConflicts($information['conflict']);

            $composerJsonFilePath = $this->createComposerJsonFilePath($smartFileInfo);

            $newFileContent = $this->composerJsonPrinter->printToString($composerJson);

            $this->removedAndAddedFilesCollector->addAddedFile(
                new AddedFileWithContent($composerJsonFilePath, $newFileContent)
            );
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
        return file_exists($this->createComposerJsonFilePath($smartFileInfo));
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
            self::AUTOLOAD_DEV => [],
            self::AUTOLOAD => [],
            'require' => [],
            'conflict' => [],
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

                if (! $this->valueResolver->isValues($item->key, [
                    self::DESCRIPTION,
                    self::VERSION,
                    self::AUTHOR,
                    self::AUTHOR_EMAIL,
                    self::AUTOLOAD_DEV,
                    self::AUTOLOAD,
                    self::CONSTRAINTS,
                ])) {
                    continue;
                }

                $itemValue = $this->transformNodeValueToPlainValue($item->value);

                if (null === $itemValue) {
                    continue;
                }

                $information[$item->key->value] = $itemValue;
            }
        }

        if (array_key_exists(self::AUTHOR, $information) && array_key_exists(self::AUTHOR_EMAIL, $information)) {
            $information[self::AUTHORS] = [
                'name' => $information[self::AUTHOR],
                'email' => $information[self::AUTHOR_EMAIL],
            ];
        }

        foreach ([
            'depends' => 'require',
            'conflicts' => 'conflict',
        ] as $dependency => $composerSection) {
            if (array_key_exists(self::CONSTRAINTS, $information) && array_key_exists(
                'depends',
                $information[self::CONSTRAINTS]
            )) {
                $information[$composerSection] = $this->resolveDependencies(
                    (array) $information[self::CONSTRAINTS][$dependency]
                );
            }
        }

        return $information;
    }

    /**
     * @return mixed
     */
    private function transformNodeValueToPlainValue(Expr $item)
    {
        return $this->valueResolver->getValue($item);
    }

    private function resolveDependencies(array $dependencies): array
    {
        $composerDependencies = [];
        foreach ($dependencies as $dependency => $dependencyVersion) {
            $composerName = $this->resolveComposerName($dependency);
            $composerDependencies[$composerName] = '*';
        }

        return $composerDependencies;
    }

    private function resolveComposerName(string $dependency): string
    {
        $url = sprintf('https://extensions.typo3.org/composerize/%s', $dependency);
        $json = json_encode([]);

        if (false === $json) {
            throw new UnexpectedValueException('Json could not be created');
        }

        $result = file_get_contents($url, false, stream_context_create(
            [
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-type: application/json\r\n" .
                                "Accept: application/json\r\n" .
                                "Connection: close\r\n" .
                                'Content-length: ' . strlen($json) . "\r\n",
                    'content' => $json,
                ],
            ]
        ));

        if (false === $result) {
            throw new UnexpectedValueException(sprintf('Could not fetch data for url "%s"', $url));
        }

        $jsonData = json_decode($result, true);

        return $jsonData['name'];
    }
}
