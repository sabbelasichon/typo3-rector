<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PhpStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;
use PHPStan\Type\FileTypeMapper;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;

final class AddSeeDocBlockForRectorClass implements Rule
{
    /**
     * @var string[]
     */
    private static $allowedClassesWithNonSeeDocBlock = [
        RenameClassMapAliasRector::class,
    ];

    /**
     * @var Broker
     */
    private $broker;

    /**
     * @var FileTypeMapper
     */
    private $fileTypeMapper;

    public function __construct(Broker $broker, FileTypeMapper $fileTypeMapper)
    {
        $this->broker = $broker;
        $this->fileTypeMapper = $fileTypeMapper;
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     *
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $className = $node->name;
        if (null === $className) {
            return [];
        }

        $fullyQualifiedClassName = $scope->getNamespace() . '\\' . $className;

        $classReflection = $this->broker->getClass($fullyQualifiedClassName);

        if (!$classReflection->isSubclassOf(PhpRectorInterface::class)) {
            return [];
        }

        if (in_array($fullyQualifiedClassName, self::$allowedClassesWithNonSeeDocBlock, true)) {
            return [];
        }

        $docComment = $node->getDocComment();
        if (null === $docComment) {
            return [sprintf('You must provide the @see docBlock for Rector %s', $className)];
        }

        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $scope->getFile(),
            $classReflection->getName(),
            null,
            null,
            $docComment->getText()
        );

        foreach ($resolvedPhpDoc->getPhpDocNode()->getTags() as $tagNode) {
            if ('@see' === $tagNode->name) {
                return [];
            }
        }

        return [sprintf('You must provide the @see docBlock for Rector %s', $className)];
    }
}
