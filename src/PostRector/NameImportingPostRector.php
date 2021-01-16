<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PostRector;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Name;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Core\Configuration\Option;
use Rector\NodeTypeResolver\FileSystem\CurrentFileInfoProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockNameImporter;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\PostRector\Rector\AbstractPostRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

final class NameImportingPostRector extends AbstractPostRector
{
    /**
     * @var string
     * @see https://regex101.com/r/s7Rv0c/1
     */
    private const ONLY_ENDS_WITH_ASTERISK_REGEX = '#^[^*](.*?)\*$#';

    /**
     * @var string
     * @see https://regex101.com/r/I2z414/1
     */
    private const ONLY_STARTS_WITH_ASTERISK_REGEX = '#^\*(.*?)[^*]$#';

    /**
     * @var ParameterProvider
     */
    private $parameterProvider;

    /**
     * @var NameImporter
     */
    private $nameImporter;

    /**
     * @var DocBlockNameImporter
     */
    private $docBlockNameImporter;

    /**
     * @var ClassNameImportSkipper
     */
    private $classNameImportSkipper;

    /**
     * @var CurrentFileInfoProvider
     */
    private $currentFileInfoProvider;

    public function __construct(
        ParameterProvider $parameterProvider,
        NameImporter $nameImporter,
        DocBlockNameImporter $docBlockNameImporter,
        ClassNameImportSkipper $classNameImportSkipper,
        CurrentFileInfoProvider $currentFileInfoProvider
    ) {
        $this->parameterProvider = $parameterProvider;
        $this->nameImporter = $nameImporter;
        $this->docBlockNameImporter = $docBlockNameImporter;
        $this->classNameImportSkipper = $classNameImportSkipper;
        $this->currentFileInfoProvider = $currentFileInfoProvider;
    }

    public function enterNode(Node $node): ?Node
    {
        $autoImportNames = (bool) $this->parameterProvider->provideParameter(Typo3Option::AUTO_IMPORT_NAMES);
        if (! $autoImportNames) {
            return null;
        }

        if ($this->shouldSkip($this)) {
            return null;
        }

        if ($node instanceof Name) {
            return $this->processNodeName($node);
        }

        $importDocBlocks = (bool) $this->parameterProvider->provideParameter(Option::IMPORT_DOC_BLOCKS);
        if (! $importDocBlocks) {
            return null;
        }

        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $node->getAttribute(AttributeKey::PHP_DOC_INFO);
        if (null === $phpDocInfo) {
            return null;
        }

        $hasChanged = $this->docBlockNameImporter->importNames($phpDocInfo, $node);
        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    public function getPriority(): int
    {
        // The \Rector\PostRector\Rector\NameImportingPostRector::class from Rector itself uses 600, so we go one level up
        return 601;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Imports fully qualified class names in parameter types, return types, extended classes, implemented, interfaces and even docblocks',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$someClass = new \Some\FullyQualified\SomeClass();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Some\FullyQualified\SomeClass;

$someClass = new SomeClass();
CODE_SAMPLE
                ),
            ]
        );
    }

    private function shouldSkip(PostRectorInterface $postRector): bool
    {
        $smartFileInfo = $this->currentFileInfoProvider->getSmartFileInfo();

        if (! $smartFileInfo instanceof SmartFileInfo) {
            return false;
        }

        $skip = $this->parameterProvider->provideArrayParameter(Option::SKIP);
        if ([] === $skip) {
            return false;
        }

        $rectorClass = get_class($postRector);
        if (! array_key_exists($rectorClass, $skip)) {
            return false;
        }

        $locations = $skip[$rectorClass];
        $filePathName = $smartFileInfo->getPathName();
        if (in_array($filePathName, $locations, true)) {
            return true;
        }

        $fileName = $smartFileInfo->getFileName();
        foreach ($locations as $location) {
            $ignoredPath = $this->normalizeForFnmatch($location);

            if ($smartFileInfo->endsWith($ignoredPath) || $smartFileInfo->doesFnmatch($ignoredPath)) {
                return true;
            }

            if (rtrim($ignoredPath, '\/') . DIRECTORY_SEPARATOR . $fileName === $filePathName) {
                return true;
            }
        }

        return false;
    }

    private function processNodeName(Name $name): ?Node
    {
        $importName = $this->getName($name);

        if (! is_callable($importName)) {
            return $this->nameImporter->importName($name);
        }

        if (substr_count($name->toCodeString(), '\\') > 1
            && $this->classNameImportSkipper->isFoundInUse($name)
            && ! function_exists($name->getLast())) {
            return null;
        }

        return $this->nameImporter->importName($name);
    }

    /**
     * "value*" → "*value*"
     * "*value" → "*value*"
     */
    private function normalizeForFnmatch(string $path): string
    {
        // ends with *
        if (Strings::match($path, self::ONLY_ENDS_WITH_ASTERISK_REGEX)) {
            return '*' . $path;
        }

        // starts with *
        if (Strings::match($path, self::ONLY_STARTS_WITH_ASTERISK_REGEX)) {
            return $path . '*';
        }

        return $path;
    }
}
