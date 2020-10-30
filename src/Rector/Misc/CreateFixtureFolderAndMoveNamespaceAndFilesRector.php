<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Misc;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Return_;
use Rector\Autodiscovery\ValueObject\NodesWithFileDestination;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\FileSystemRector\Rector\AbstractFileMovingFileSystemRector;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Symplify\SmartFileSystem\SmartFileInfo;

final class CreateFixtureFolderAndMoveNamespaceAndFilesRector extends AbstractFileMovingFileSystemRector
{
    /**
     * @var string
     */
    private const _FIXTURE = '/Fixture';

    /**
     * @var string
     */
    private const MAJOR = 'major';

    /**
     * @var string
     */
    private const MINOR = 'minor';

    /**
     * @var string
     */
    private const SHORT_CLASS_NAME = 'shortClassName';

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Create fixture folder and move namespace', []);
    }

    public function refactor(SmartFileInfo $smartFileInfo): void
    {
        $nodes = $this->parseFileInfoToNodes($smartFileInfo);
        /** @var Class_|null $class */
        $class = $this->betterNodeFinder->findFirstInstanceOf($nodes, Class_::class);
        if (null === $class) {
            return;
        }

        if (! $this->isObjectType($class, AbstractRectorTestCase::class)) {
            return;
        }

        $provideDataForTestMethod = $this->betterNodeFinder->findFirst($nodes, function (Node $node) {
            if (! $node instanceof ClassMethod) {
                return false;
            }
            return $this->isName($node, 'provideDataForTest');
        });

        if (null === $provideDataForTestMethod || ! $provideDataForTestMethod instanceof ClassMethod) {
            return;
        }

        $rectorClassMethod = $this->betterNodeFinder->findFirst($nodes, function (Node $node) {
            if (! $node instanceof ClassMethod) {
                return false;
            }
            return $this->isName($node, 'getRectorClass');
        });

        if (null === $rectorClassMethod || ! $rectorClassMethod instanceof ClassMethod || null === $rectorClassMethod->stmts) {
            return;
        }

        $returnClass = $this->betterNodeFinder->findFirst($rectorClassMethod->stmts, function (Node $node) {
            return $node instanceof Return_;
        });

        if (null === $returnClass || ! $returnClass instanceof Return_) {
            return;
        }

        if (null === $returnClass->expr) {
            return;
        }

        if (! $this->isObjectType($returnClass->expr, AbstractRector::class)) {
            return;
        }

        $collectedFiles = $this->changeProvideDataForTestMethod($provideDataForTestMethod);

        $className = $this->getValue($returnClass->expr);

        preg_match(
            '#Ssch\\\TYPO3Rector\\\Rector\\\v(?P<major>\d+)\\\v(?P<minor>\d+)\\\(?P<shortClassName>\w+)Rector#',
            $className,
            $matches
        );

        if ([] === $matches) {
            return;
        }

        /** @var Namespace_|null $currentNamespace */
        $currentNamespace = $this->betterNodeFinder->findFirstInstanceOf($nodes, Namespace_::class);

        // file without namespace → skip
        if (null === $currentNamespace || null === $currentNamespace->name) {
            return;
        }

        $currentNamespaceName = (string) $currentNamespace->name;
        $newNamespace = new Namespace_(new Name(sprintf(
            'Ssch\TYPO3Rector\Tests\Rector\v%s\v%s\%s',
            $matches[self::MAJOR],
            $matches[self::MINOR],
            $matches[self::SHORT_CLASS_NAME]
        )));

        $currentClassName = $currentNamespaceName . '\\' . $smartFileInfo->getBasenameWithoutSuffix();

        // change namespace to new one
        $newNamespaceName = (string) $newNamespace->name;
        $newClassName = $this->createNewClassName($smartFileInfo, $newNamespaceName);

        // classes are identical, no rename
        if ($currentClassName === $newClassName) {
            return;
        }

        // 1. rename namespace
        $this->renameNamespace($nodes, $newNamespaceName);

        // 2. return changed nodes and new file destination
        $newFileDestination = __DIR__ . sprintf(
            '/../../../tests/Rector/v%s/v%s/%s/%s',
            $matches[self::MAJOR],
            $matches[self::MINOR],
            $matches[self::SHORT_CLASS_NAME],
            $smartFileInfo->getFilename()
        );

        // Move Fixture Folder in current directory also
        $newFixtureFileDestination = __DIR__ . sprintf(
                '/../../../tests/Rector/v%s/v%s/%s/',
                $matches[self::MAJOR],
                $matches[self::MINOR],
                $matches[self::SHORT_CLASS_NAME]
            );

        $finder = new Finder();
        $finder->in($smartFileInfo->getPath() . '/Fixture/');

        if ([] !== $collectedFiles) {
            $finder->filter(static function (SplFileInfo $fileInfo) use ($collectedFiles) {
                return in_array($fileInfo->getFilename(), $collectedFiles, false);
            });
        }

        foreach ($finder as $file) {
            $fixtureSmartFileInfo = new SmartFileInfo($file->getPath());
            $this->moveFile(
                $fixtureSmartFileInfo,
                $newFixtureFileDestination . self::_FIXTURE . '/' . $file->getFilename(),
                $file->getContents()
            );
        }

        // 3. update fully qualified name of the class like - will be used further
        /** @var ClassLike $classLike */
        $classLike = $this->betterNodeFinder->findFirstInstanceOf($nodes, ClassLike::class);
        $classLike->namespacedName = new FullyQualified($newClassName);

        $nodesWithFileDestination = new NodesWithFileDestination(
            $nodes, $newFileDestination, $smartFileInfo, $currentClassName, $newClassName
        );

        $this->processNodesWithFileDestination($nodesWithFileDestination);
    }

    /**
     * @return Yield_[]
     */
    private function collectYieldNodesFromClassMethod(ClassMethod $classMethod): array
    {
        $yieldNodes = [];

        if (null === $classMethod->stmts) {
            return [];
        }

        foreach ($classMethod->stmts as $statement) {
            if (! $statement instanceof Expression) {
                continue;
            }

            if ($statement->expr instanceof Yield_) {
                $yieldNodes[] = $statement->expr;
            }
        }

        return $yieldNodes;
    }

    private function collectFilesFromYields(array $yieldNodes): array
    {
        $files = [];

        foreach ($yieldNodes as $yieldNode) {
            $files[] = basename($this->getValue($yieldNode->value->items[0]->value->args[0]->value->right));
        }

        return $files;
    }

    private function createNewClassName(SmartFileInfo $smartFileInfo, string $newNamespaceName): string
    {
        return $newNamespaceName . '\\' . $smartFileInfo->getBasenameWithoutSuffix();
    }

    /**
     * @param Node[] $nodes
     */
    private function renameNamespace(array $nodes, string $newNamespaceName): void
    {
        foreach ($nodes as $node) {
            if (! $node instanceof Namespace_) {
                continue;
            }

            $node->name = new Name($newNamespaceName);
            break;
        }
    }

    private function changeProvideDataForTestMethod(ClassMethod $provideDataForTestMethod): array
    {
        $yieldNodes = $this->collectYieldNodesFromClassMethod($provideDataForTestMethod);

        if ([] === $yieldNodes) {
            return [];
        }

        $collectedFiles = $this->collectFilesFromYields($yieldNodes);

        if ([] === $collectedFiles) {
            return [];
        }

        $this->removeNodes($yieldNodes);

        $directoryArg = new Concat(new Dir(), new String_(self::_FIXTURE));
        $provideDataForTestMethod->stmts[] = new Return_($this->createMethodCall(
            'this',
            'yieldFilesFromDirectory',
            [$directoryArg]
        ));

        return $collectedFiles;
    }
}
