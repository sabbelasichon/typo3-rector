<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Migrations;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Configuration\ChangeConfiguration;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\ConfiguredCodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Renaming\NodeManipulator\ClassRenamer;

final class RenameClassMapAliasRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const CLASS_ALIAS_MAPS = 'class_alias_maps';

    /**
     * @var array<string, string>
     */
    private $oldToNewClasses = [];

    /**
     * @var ClassRenamer
     */
    private $classRenamer;

    /**
     * @var ChangeConfiguration
     */
    private $changeConfiguration;

    public function __construct(ChangeConfiguration $changeConfiguration, ClassRenamer $classRenamer)
    {
        $this->classRenamer = $classRenamer;
        $this->changeConfiguration = $changeConfiguration;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Replaces defined classes by new ones.', [
            new ConfiguredCodeSample(
                <<<'PHP'
namespace App;

use t3lib_div;

function someFunction()
{
    t3lib_div::makeInstance(\tx_cms_BackendLayout::class);
}
PHP
                ,
                <<<'PHP'
namespace App;

use TYPO3\CMS\Core\Utility\GeneralUtility;

function someFunction()
{
    GeneralUtility::makeInstance(\TYPO3\CMS\Backend\View\BackendLayoutView::class);
}
PHP
                ,
                [
                    self::CLASS_ALIAS_MAPS => 'config/Migrations/Code/ClassAliasMap.php',
                ]
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [
            Name::class,
            Property::class,
            FunctionLike::class,
            Expression::class,
            ClassLike::class,
            Namespace_::class,
        ];
    }

    /**
     * @param Name|FunctionLike|Property $node
     */
    public function refactor(Node $node): ?Node
    {
        return $this->classRenamer->renameNode($node, $this->oldToNewClasses);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $classAliasMaps = $configuration[self::CLASS_ALIAS_MAPS] ?? [];
        foreach ($classAliasMaps as $file) {
            $filePath = realpath($file);
            if (false !== $filePath && file_exists($filePath)) {
                $classAliasMap = require $filePath;

                foreach ($classAliasMap as $oldClass => $newClass) {
                    $this->oldToNewClasses[$oldClass] = $newClass;
                }
            }
        }

        if ([] !== $this->oldToNewClasses) {
            $this->changeConfiguration->setOldToNewClasses($this->oldToNewClasses);
        }
    }
}
