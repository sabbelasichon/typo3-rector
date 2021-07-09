<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Restoration\ValueObject\CompleteImportForPartialAnnotation;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;

final class ImportExtbaseAnnotationIfMissingFactory
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private  UseNodesToAddCollector $useNodesToAddCollector,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function addExtbaseAliasAnnotationIfMissing(Node $node): void
    {
        $namespace = $this->betterNodeFinder->findFirstPrevious(
            $node,
            fn (Node $node): bool => $node instanceof Namespace_
        );

        $completeImportForPartialAnnotation = new CompleteImportForPartialAnnotation(
            'TYPO3\CMS\Extbase\Annotation',
            'Extbase'
        );
        if ($namespace instanceof Namespace_ && $this->isImportMissing(
            $namespace,
            $completeImportForPartialAnnotation
        )) {
            $this->useNodesToAddCollector->addUseImport(
                new AliasedObjectType('Extbase', 'TYPO3\CMS\Extbase\Annotation')
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
