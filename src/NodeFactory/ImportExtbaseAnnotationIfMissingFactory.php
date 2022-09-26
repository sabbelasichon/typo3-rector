<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Ssch\TYPO3Rector\ValueObject\CompleteImportForPartialAnnotation;

final class ImportExtbaseAnnotationIfMissingFactory
{
    /**
     * @readonly
     */
    private BetterNodeFinder $betterNodeFinder;

    /**
     * @readonly
     */
    private UseNodesToAddCollector $useNodesToAddCollector;

    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    public function __construct(
        BetterNodeFinder $betterNodeFinder,
        UseNodesToAddCollector $useNodesToAddCollector,
        NodeNameResolver $nodeNameResolver
    ) {
        $this->betterNodeFinder = $betterNodeFinder;
        $this->useNodesToAddCollector = $useNodesToAddCollector;
        $this->nodeNameResolver = $nodeNameResolver;
    }

    public function addExtbaseAliasAnnotationIfMissing(Node $node): void
    {
        $namespace = $this->betterNodeFinder->findParentType($node, Namespace_::class);

        $completeImportForPartialAnnotation = new CompleteImportForPartialAnnotation(
            'TYPO3\CMS\Extbase\Annotation',
            'Extbase'
        );
        if ($namespace instanceof Namespace_ && $this->isImportMissing(
            $namespace,
            $completeImportForPartialAnnotation
        )) {
            $this->useNodesToAddCollector->addUseImport(
                new FullyQualifiedObjectType('TYPO3\CMS\Extbase\Annotation as Extbase')
            );
        }
    }

    private function isImportMissing(
        Namespace_ $namespace,
        CompleteImportForPartialAnnotation $completeImportForPartialAnnotation
    ): bool {
        foreach ($namespace->stmts as $stmt) {
            if (! $stmt instanceof Use_) {
                continue;
            }

            $useUse = $stmt->uses[0];
            // already there
            if (! $this->nodeNameResolver->isName($useUse->name, $completeImportForPartialAnnotation->getUse())) {
                continue;
            }

            if ((string) $useUse->alias !== $completeImportForPartialAnnotation->getAlias()) {
                continue;
            }

            return false;
        }

        return true;
    }
}
