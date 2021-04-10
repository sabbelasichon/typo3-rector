<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Rules;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;
use PHPStan\Type\FileTypeMapper;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Ssch\TYPO3Rector\Rector\General\ConvertTypo3ConfVarsRector;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rules\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRector;

/**
 * @see \Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddChangelogDocBlockForRectorClass\AddChangelogDocBlockForRectorClassTest
 */
final class AddChangelogDocBlockForRectorClass implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Provide @changelog doc block for "%s" Rector class';

    /**
     * @var string[]
     */
    private const ALLOWED_CLASSES_WITH_NON_CHANGELOG_DOC_BLOCK = [
        RenameClassMapAliasRector::class,
        AddCodeCoverageIgnoreToMethodRectorDefinitionRector::class,
        ConvertTypo3ConfVarsRector::class,
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

        if (! $classReflection->isSubclassOf(PhpRectorInterface::class)) {
            return [];
        }

        if (in_array($fullyQualifiedClassName, self::ALLOWED_CLASSES_WITH_NON_CHANGELOG_DOC_BLOCK, true)) {
            return [];
        }

        $docComment = $node->getDocComment();
        if (null === $docComment) {
            return [sprintf(self::ERROR_MESSAGE, $className)];
        }

        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $scope->getFile(),
            $classReflection->getName(),
            null,
            null,
            $docComment->getText()
        );

        $phpDocString = $resolvedPhpDoc->getPhpDocString();
        if (Strings::contains($phpDocString, '@changelog')) {
            return [];
        }

        return [sprintf(self::ERROR_MESSAGE, $className)];
    }
}
