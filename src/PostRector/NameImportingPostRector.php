<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PostRector;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Name;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Core\Configuration\Option;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\FileSystem\CurrentFileInfoProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockNameImporter;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\PostRector\Rector\AbstractPostRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
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
     * @var CurrentFileInfoProvider
     */
    private $currentFileInfoProvider;

    /**
     * @var bool
     */
    private $importDocBlocks = false;

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

    public function __construct(
        CurrentFileInfoProvider $currentFileInfoProvider,
        ParameterProvider $parameterProvider,
        NameImporter $nameImporter,
        DocBlockNameImporter $docBlockNameImporter
    ) {
        $this->parameterProvider = $parameterProvider;
        $this->nameImporter = $nameImporter;
        $this->importDocBlocks = (bool) $parameterProvider->provideParameter(Option::IMPORT_DOC_BLOCKS);
        $this->docBlockNameImporter = $docBlockNameImporter;
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
            return $this->nameImporter->importName($node);
        }

        if (! $this->importDocBlocks) {
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

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Imports fully qualified class names in parameter types, return types, extended classes, implemented, interfaces and even docblocks'
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
