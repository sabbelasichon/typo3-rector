<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Rules;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\FileTypeMapper;
use Rector\Core\Contract\Rector\PhpRectorInterface;

/**
 * @see \Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition\AddCodeCoverageIgnoreForRectorDefinitionTest
 */
final class AddCodeCoverageIgnoreForRectorDefinition implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Provide @codeCoverageIgnore doc block for "%s" RectorDefinition method';

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
        return ClassMethod::class;
    }

    /**
     * @param Node|ClassMethod $node
     *
     * @return string[]
     * @throws ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $scope->isInClass()) {
            throw new ShouldNotHappenException();
        }

        $classReflection = $scope->getClassReflection();

        if (null === $classReflection) {
            return [];
        }

        if (! $classReflection->isSubclassOf(PhpRectorInterface::class)) {
            return [];
        }

        $methodName = $node->name->toString();

        if ('getRuleDefinition' !== $methodName) {
            return [];
        }

        $className = $classReflection->getName();

        $docComment = $node->getDocComment();
        if (null === $docComment) {
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
        if (Strings::contains($phpDocString, '@codeCoverageIgnore')) {
            return [];
        }

        return [sprintf(self::ERROR_MESSAGE, $className)];
    }
}
