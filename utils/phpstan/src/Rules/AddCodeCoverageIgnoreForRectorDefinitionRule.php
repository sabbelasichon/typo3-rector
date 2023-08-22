<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Rules;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\FileTypeMapper;
use Rector\Core\Contract\Rector\PhpRectorInterface;

/**
 * @see \Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition\AddCodeCoverageIgnoreForRectorDefinitionTest
 * @implements Rule<ClassMethod>
 */
final class AddCodeCoverageIgnoreForRectorDefinitionRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Provide @codeCoverageIgnore doc block for "%s" RectorDefinition method';

    /**
     * @readonly
     */
    private FileTypeMapper $fileTypeMapper;

    public function __construct(FileTypeMapper $fileTypeMapper)
    {
        $this->fileTypeMapper = $fileTypeMapper;
    }

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $scope->isInClass()) {
            throw new ShouldNotHappenException();
        }

        $classReflection = $scope->getClassReflection();

        if (! $classReflection->isSubclassOf(PhpRectorInterface::class)) {
            return [];
        }

        $methodName = $node->name->toString();

        if ($methodName !== 'getRuleDefinition') {
            return [];
        }

        $className = $classReflection->getName();

        $docComment = $node->getDocComment();
        if (! $docComment instanceof Doc) {
            return [sprintf(self::ERROR_MESSAGE, $className)];
        }

        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $scope->getFile(),
            $classReflection->getName(),
            null,
            $methodName,
            $docComment->getText()
        );

        $phpDocString = $resolvedPhpDoc->getPhpDocString();
        if (\str_contains($phpDocString, '@codeCoverageIgnore')) {
            return [];
        }

        return [sprintf(self::ERROR_MESSAGE, $className)];
    }
}
