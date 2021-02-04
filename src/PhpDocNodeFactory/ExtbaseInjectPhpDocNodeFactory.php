<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PhpDocNodeFactory;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Rector\BetterPhpDocParser\Contract\SpecificPhpDocNodeFactoryInterface;
use Rector\BetterPhpDocParser\PhpDocNodeFactory\AbstractPhpDocNodeFactory;
use Ssch\TYPO3Rector\ValueObject\PhpDocNode\Doctrine\InjectTagValueNode;
use TYPO3\CMS\Extbase\Annotation\Inject;

final class ExtbaseInjectPhpDocNodeFactory extends AbstractPhpDocNodeFactory implements SpecificPhpDocNodeFactoryInterface
{
    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return ['TYPO3\CMS\Extbase\Annotation\Inject'];
    }

    /**
     * @return InjectTagValueNode|null
     */
    public function createFromNodeAndTokens(
        Node $node,
        TokenIterator $tokenIterator,
        string $annotationClass
    ): ?PhpDocTagValueNode {
        if (! $node instanceof Property) {
            return null;
        }

        $inject = $this->nodeAnnotationReader->readPropertyAnnotation($node, $annotationClass);

        if (! $inject instanceof Inject) {
            return null;
        }

        // needed for proper doc block formatting
        $annotationContent = $this->resolveContentFromTokenIterator($tokenIterator);

        $items = $this->annotationItemsResolver->resolve($inject);
        return new InjectTagValueNode($items, $annotationContent);
    }
}
