<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Visitors;

use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Scalar as ScalarValue;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Nette\Utils\Strings;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\ConvertToPhpFileInterface;
use Ssch\TYPO3Rector\Template\TemplateFinder;
use Symfony\Component\VarExporter\VarExporter;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Breaking-87623-ReplaceConfigpersistenceclassesTyposcriptConfiguration.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript\TypoScriptProcessorTest
 */
final class ExtbasePersistenceVisitor extends AbstractVisitor implements ConvertToPhpFileInterface, ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const FILENAME = 'filename';

    /**
     * @var string
     */
    private const SUBCLASSES = 'subclasses';

    private string $filename;

    /**
     * @var array<string, array<string, mixed>>
     */
    private static array $persistenceArray = [];

    private SmartFileInfo $fileTemplate;

    public function __construct(Configuration $configuration, TemplateFinder $templateFinder)
    {
        $this->filename = dirname(
            (string) $configuration->getMainConfigFilePath()
        ) . '/Configuration_Extbase_Persistence_Classes.php';

        $this->fileTemplate = $templateFinder->getExtbasePersistenceConfiguration();
    }

    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof Assignment) {
            return;
        }

        if (! Strings::contains($statement->object->absoluteName, 'persistence.classes')) {
            return;
        }

        $paths = explode('.', $statement->object->absoluteName);
        // Strip the first parts like config.tx_extbase.persistence.classes
        $paths = array_slice($paths, 4);

        $this->extractSubClasses($paths, $statement);
        $this->extractMapping('tableName', $paths, $statement);
        $this->extractMapping('recordType', $paths, $statement);
        $this->extractColumns($paths, $statement);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert extbase TypoScript persistence configuration to classes one', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
config.tx_extbase.persistence.classes {
    GeorgRinger\News\Domain\Model\FileReference {
        mapping {
            tableName = sys_file_reference
        }
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    \GeorgRinger\News\Domain\Model\FileReference::class => [
        'tableName' => 'sys_file_reference',
    ],
];
CODE_SAMPLE
,
                [
                    self::FILENAME => 'path/to/Configuration/Extbase/Persistence/Classes.php',
                ]
            ),
        ]);
    }

    public function convert(): ?AddedFileWithContent
    {
        if ([] === self::$persistenceArray) {
            return null;
        }

        $content = str_replace(
            '__PERSISTENCE_ARRAY__',
            VarExporter::export(self::$persistenceArray),
            $this->fileTemplate->getContents()
        );

        return new AddedFileWithContent($this->filename, $content);
    }

    public function getMessage(): string
    {
        return 'We have converted from TypoScript extbase persistence to a PHP File';
    }

    public function configure(array $configuration): void
    {
        $filename = $configuration[self::FILENAME] ?? null;

        if (null !== $filename) {
            $this->filename = $filename;
        }
    }

    /**
     * @param string[] $paths
     */
    private function extractSubClasses(array $paths, Assignment $statement): void
    {
        if (! in_array(self::SUBCLASSES, $paths, true)) {
            return;
        }

        $className = $paths[0];
        if (! array_key_exists($className, self::$persistenceArray)) {
            self::$persistenceArray[$className] = [
                self::SUBCLASSES => [],
            ];
        }

        /** @var ScalarValue $scalarValue */
        $scalarValue = $statement->value;
        self::$persistenceArray[$className][self::SUBCLASSES][] = $scalarValue->value;
    }

    /**
     * @param string[] $paths
     */
    private function extractMapping(string $name, array $paths, Assignment $statement): void
    {
        if (! in_array($name, $paths, true)) {
            return;
        }

        $className = $paths[0];
        if (! array_key_exists($className, self::$persistenceArray)) {
            self::$persistenceArray[$className] = [];
        }

        /** @var ScalarValue $scalar */
        $scalar = $statement->value;
        self::$persistenceArray[$className][$name] = $scalar->value;
    }

    /**
     * @param string[] $paths
     */
    private function extractColumns(array $paths, Assignment $statement): void
    {
        if (! in_array('columns', $paths, true)) {
            return;
        }

        $className = $paths[0];
        if (! array_key_exists($className, self::$persistenceArray)) {
            self::$persistenceArray[$className]['properties'] = [];
        }

        $fieldName = $paths[3];

        /** @var ScalarValue $scalar */
        $scalar = $statement->value;

        self::$persistenceArray[$className]['properties'][$scalar->value]['fieldname'] = $fieldName;
    }
}
