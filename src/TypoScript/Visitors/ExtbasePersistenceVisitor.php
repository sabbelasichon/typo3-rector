<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Visitors;

use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Reporting\ValueObject\Report;
use Ssch\TYPO3Rector\TypoScript\ConvertToPhpFileInterface;
use Ssch\TYPO3Rector\ValueObject\TypoScriptToPhpFile;
use Symfony\Component\VarExporter\VarExporter;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ExtbasePersistenceVisitor extends AbstractVisitor implements ConvertToPhpFileInterface
{
    /**
     * @var string
     */
    private const GENERATED_FILE_TEMPLATE = <<<'CODE_SAMPLE'
<?php

declare(strict_types = 1);

return %s;
CODE_SAMPLE;

    /**
     * @var string
     */
    private const SUBCLASSES = 'subclasses';

    /**
     * @var array
     */
    private static $persistenceArray = [];

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
            new CodeSample(
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
            ),
        ]);
    }

    public function convert(): TypoScriptToPhpFile
    {
        $content = sprintf(self::GENERATED_FILE_TEMPLATE, VarExporter::export(self::$persistenceArray));

        return new TypoScriptToPhpFile('Extbase_Persistence_Classes', $content);
    }

    public function getReport(): Report
    {
        return new Report(
            'We have converted from TypoScript extbase persistence to a PHP File',
            $this,
            ['Move and maybe divide file to appropriate location in your extension']
        );
    }

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

        self::$persistenceArray[$className][self::SUBCLASSES][] = $statement->value->value;
    }

    private function extractMapping(string $name, array $paths, Assignment $statement): void
    {
        if (! in_array($name, $paths, true)) {
            return;
        }

        $className = $paths[0];
        if (! array_key_exists($className, self::$persistenceArray)) {
            self::$persistenceArray[$className] = [];
        }

        self::$persistenceArray[$className][$name] = $statement->value->value;
    }

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
        self::$persistenceArray[$className]['properties'][$statement->value->value]['fieldname'] = $fieldName;
    }
}
