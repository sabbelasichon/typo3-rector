<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Ssch\TYPO3Rector\Helper\SymfonyYamlParser;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-96659-ContentObjectRegistrationViaServiceConfiguration.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector\ContentObjectRegistrationViaServiceConfigurationRectorTest
 */
final class ContentObjectRegistrationViaServiceConfigurationRector extends AbstractRector
{
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;

    /**
     * @readonly
     */
    private SymfonyYamlParser $symfonyYamlParser;

    public function __construct(
        ReflectionProvider $reflectionProvider,
        FilesFinder $filesFinder,
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        SymfonyYamlParser $symfonyYamlParser
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->filesFinder = $filesFinder;
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->symfonyYamlParser = $symfonyYamlParser;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $node->var instanceof ArrayDimFetch) {
            return null;
        }

        if (! $node->var->dim instanceof Expr) {
            return null;
        }

        $contentObjectName = $this->valueResolver->getValue($node->var->dim);

        if ($contentObjectName === null || $contentObjectName === '') {
            return null;
        }

        $contentObjectNameClass = $this->valueResolver->getValue($node->expr);

        if ($contentObjectNameClass === null || $contentObjectNameClass === '') {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($contentObjectNameClass)) {
            return null;
        }

        $extEmConf = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo(
            new SmartFileInfo($this->file->getFilePath())
        );

        if (! $extEmConf instanceof SmartFileInfo) {
            return null;
        }

        $existingServicesYamlFilePath = $extEmConf->getRealPathDirectory() . '/Configuration/Services.yaml';

        $yamlConfiguration = $this->getYamlConfiguration($existingServicesYamlFilePath);

        if (! isset($yamlConfiguration['services'])) {
            $yamlConfiguration['services'] = [];
        }

        if (! isset($yamlConfiguration['services'][$contentObjectNameClass])) {
            $yamlConfiguration['services'][$contentObjectNameClass]['tags'][] = [
                'name' => 'frontend.contentobject',
                'identifier' => sprintf('%s', $contentObjectName),
            ];
        }

        $yamlConfigurationAsYaml = $this->symfonyYamlParser->dump($yamlConfiguration);

        $servicesYaml = new AddedFileWithContent($existingServicesYamlFilePath, $yamlConfigurationAsYaml);
        $this->removedAndAddedFilesCollector->addAddedFile($servicesYaml);

        $this->nodeRemover->removeNode($node);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('ContentObject Registration via service configuration', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'][Multivalue::CONTENT_OBJECT_NAME] = Multivalue::class;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// Remove node and add or modify existing Services.yaml in Configuration/Services.yaml
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(Assign $node): bool
    {
        if (! $node->var instanceof ArrayDimFetch) {
            return true;
        }

        if (! $node->var->var instanceof ArrayDimFetch) {
            return true;
        }

        if (! $node->var->var->dim instanceof Expr) {
            return true;
        }

        if (! $this->valueResolver->isValue($node->var->var->dim, 'ContentObjects')) {
            return true;
        }

        if (! $node->var->var->var instanceof ArrayDimFetch) {
            return true;
        }

        if (! $node->var->var->var->dim instanceof Expr) {
            return true;
        }

        return ! $this->valueResolver->isValue($node->var->var->var->dim, 'FE');
    }

    /**
     * @return array<mixed>
     */
    private function getYamlConfiguration(string $existingServicesYamlFilePath): array
    {
        if (file_exists($existingServicesYamlFilePath)) {
            return $this->symfonyYamlParser->parse(
                $existingServicesYamlFilePath,
                (string) file_get_contents($existingServicesYamlFilePath)
            );
        }

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();
        foreach ($addedFilesWithContent as $addedFileWithContent) {
            if ($addedFileWithContent->getFilePath() === $existingServicesYamlFilePath) {
                return $this->symfonyYamlParser->parse(
                    $addedFileWithContent->getFilePath(),
                    $addedFileWithContent->getFileContent()
                );
            }
        }

        return [];
    }
}
