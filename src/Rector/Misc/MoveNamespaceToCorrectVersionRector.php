<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Misc;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Autodiscovery\ValueObject\NodesWithFileDestination;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\FileSystemRector\Rector\AbstractFileMovingFileSystemRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MoveNamespaceToCorrectVersionRector extends AbstractFileMovingFileSystemRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Move namespace', []);
    }

    public function refactor(SmartFileInfo $smartFileInfo): void
    {
        $nodes = $this->parseFileInfoToNodes($smartFileInfo);

        /** @var Class_|null $class */
        $class = $this->betterNodeFinder->findFirstInstanceOf($nodes, Class_::class);
        if (null === $class) {
            return;
        }

        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $class->getAttribute(AttributeKey::PHP_DOC_INFO);

        if (! $phpDocInfo->hasByName('see')) {
            return;
        }

        $seeDocs = $phpDocInfo->getTagsByName('see');

        $targetVersion = null;
        foreach ($seeDocs as $seeDoc) {
            if (1 === preg_match(
                '#https://docs\.typo3\.org/c/typo3/cms-core/master/en-us/Changelog/(.*)/#',
                $seeDoc->value->value,
                $matches
            )) {
                $targetVersion = $matches[1];
                break;
            }
        }

        if (null === $targetVersion) {
            return;
        }

        [$major, $minor] = explode('.', $targetVersion, 2);

        /** @var Namespace_|null $currentNamespace */
        $currentNamespace = $this->betterNodeFinder->findFirstInstanceOf($nodes, Namespace_::class);

        // file without namespace → skip
        if (null === $currentNamespace || null === $currentNamespace->name) {
            return;
        }
        $currentNamespaceName = (string) $currentNamespace->name;
        $newNamespace = new Namespace_(new Name(sprintf('Ssch\TYPO3Rector\Rector\v%s\v%s', $major, $minor)));

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
        $newFileDestination = __DIR__ . sprintf('/../v%s/v%s/%s', $major, $minor, $smartFileInfo->getFilename());

        // 3. update fully qualifed name of the class like - will be used further
        /** @var ClassLike $classLike */
        $classLike = $this->betterNodeFinder->findFirstInstanceOf($nodes, ClassLike::class);
        $classLike->namespacedName = new FullyQualified($newClassName);

        $nodesWithFileDestination = new NodesWithFileDestination(
            $nodes, $newFileDestination, $smartFileInfo, $currentClassName, $newClassName
        );

        $this->processNodesWithFileDestination($nodesWithFileDestination);
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
}
