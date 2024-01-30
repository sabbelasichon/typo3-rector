<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\Rector\General;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use Rector\Configuration\RenamedClassesDataCollector;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Rector\AbstractRector;
use Rector\Renaming\NodeManipulator\ClassRenamer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\Migrations\RenameClassMapAliasRectorTest
 */
final class RenameClassMapAliasRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface
{
    /**
     * @api
     * @var string
     */
    public const CLASS_ALIAS_MAPS = 'class_alias_maps';

    /**
     * @api
     * @var string
     */
    public const CLASSES_TO_SKIP = 'classes_to_skip';

    /**
     * @var array<string, string>
     */
    private array $oldToNewClasses = [];

    /**
     * @var string[]
     */
    private array $classesToSkip = [
        // can be string
        'language',
        'template',
    ];

    /**
     * @readonly
     */
    private RenamedClassesDataCollector $renamedClassesDataCollector;

    /**
     * @readonly
     */
    private ClassRenamer $classRenamer;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(RenamedClassesDataCollector $renamedClassesDataCollector, ClassRenamer $classRenamer, FileInfoFactory $fileInfoFactory)
    {
        $this->renamedClassesDataCollector = $renamedClassesDataCollector;
        $this->classRenamer = $classRenamer;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces defined classes by new ones.', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
namespace App;

use t3lib_div;

function someFunction()
{
    t3lib_div::makeInstance(\tx_cms_BackendLayout::class);
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
namespace App;

use TYPO3\CMS\Core\Utility\GeneralUtility;

function someFunction()
{
    GeneralUtility::makeInstance(\TYPO3\CMS\Backend\View\BackendLayoutView::class);
}
CODE_SAMPLE
                ,
                [
                    self::CLASS_ALIAS_MAPS => 'config/Migrations/Code/ClassAliasMap.php',
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            FileWithoutNamespace::class,
            Name::class,
            Property::class,
            FunctionLike::class,
            Expression::class,
            ClassLike::class,
            Namespace_::class,
            String_::class,
        ];
    }

    /**
     * @param FunctionLike|Name|ClassLike|Expression|Namespace_|Property|FileWithoutNamespace|String_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof String_) {
            return $this->stringClassNameToClassConstantRectorIfPossible($node);
        }

        return $this->classRenamer->renameNode($node, $this->oldToNewClasses, null);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $classAliasMaps = $configuration[self::CLASS_ALIAS_MAPS] ?? $configuration;

        foreach ($classAliasMaps as $file) {
            $filePath = $this->fileInfoFactory->createFileInfoFromPath($file);
            $classAliasMap = require $filePath->getRealPath();
            foreach ($classAliasMap as $oldClass => $newClass) {
                if (! is_string($oldClass) || ! is_string($newClass)) {
                    continue;
                }
                $this->oldToNewClasses[$oldClass] = $newClass;
            }
        }

        if ($this->oldToNewClasses !== []) {
            $this->renamedClassesDataCollector->addOldToNewClasses($this->oldToNewClasses);
        }

        if (isset($configuration[self::CLASSES_TO_SKIP])) {
            $this->classesToSkip = $configuration[self::CLASSES_TO_SKIP];
        }
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::CLASSNAME_CONSTANT;
    }

    private function stringClassNameToClassConstantRectorIfPossible(String_ $node): ?Node
    {
        $classLikeName = $node->value;

        // remove leading slash
        $classLikeName = ltrim($classLikeName, '\\');
        if ($classLikeName === '') {
            return null;
        }

        if (! array_key_exists($classLikeName, $this->oldToNewClasses)) {
            return null;
        }

        if (self::isInArrayInsensitive($classLikeName, $this->classesToSkip)) {
            return null;
        }

        $newClassName = $this->oldToNewClasses[$classLikeName];

        return $this->nodeFactory->createClassConstReference($newClassName);
    }

    /**
     * @param string[] $array
     */
    private static function isInArrayInsensitive(string $checkedItem, array $array): bool
    {
        $checkedItem = strtolower($checkedItem);
        foreach ($array as $singleArray) {
            if (strtolower($singleArray) === $checkedItem) {
                return true;
            }
        }

        return false;
    }
}
