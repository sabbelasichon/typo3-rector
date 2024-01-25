<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use Nette\Utils\Json;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symfony\Component\Finder\SplFileInfo;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Throwable;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Important-82692-GuidelinesForExtensionFiles.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector\ReplaceExtKeyWithExtensionKeyFromFolderNameTest
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector\ReplaceExtKeyWithExtensionKeyFromComposerJsonNameRectorTest
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector\ReplaceExtKeyWithExtensionKeyFromComposerJsonExtensionKeyExtraSectionRectorTest
 */
final class ReplaceExtKeyWithExtensionKeyRector extends AbstractScopeAwareRector
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(FilesFinder $filesFinder, FileInfoFactory $fileInfoFactory)
    {
        $this->filesFinder = $filesFinder;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace $_EXTKEY with extension key', [
            new CodeSample(
                <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin(
    'Foo.'.$_EXTKEY,
    'ArticleTeaser',
    [
        'FooBar' => 'baz',
    ]
);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin(
    'Foo.'.'bar',
    'ArticleTeaser',
    [
        'FooBar' => 'baz',
    ]
);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        $fileInfo = $this->fileInfoFactory->createFileInfoFromPath($this->file->getFilePath());

        if ($this->filesFinder->isExtEmconf($this->file->getFilePath())) {
            return null;
        }

        if (! $this->isExtensionKeyVariable($node)) {
            return null;
        }

        $extEmConf = $this->createExtensionKeyFromFolder($fileInfo);

        if (! $extEmConf instanceof SplFileInfo) {
            return null;
        }

        if ($scope->isInFirstLevelStatement()) {
            return null;
        }

        $extensionKey = $this->resolveExtensionKeyByComposerJson($extEmConf);

        if ($extensionKey === null) {
            $extensionKey = basename(dirname($extEmConf->getRealPath()));
        }

        return new String_($extensionKey);
    }

    private function isExtensionKeyVariable(Variable $variable): bool
    {
        return $this->isName($variable, '_EXTKEY');
    }

    private function createExtensionKeyFromFolder(SplFileInfo $fileInfo): ?SplFileInfo
    {
        return $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($fileInfo);
    }

    private function resolveExtensionKeyByComposerJson(SplFileInfo $extEmConf): ?string
    {
        try {
            $composerJson = $this->fileInfoFactory->createFileInfoFromPath(
                dirname($extEmConf->getRealPath()) . '/composer.json'
            );
            $json = Json::decode($composerJson->getContents(), Json::FORCE_ARRAY);

            if (isset($json['extra']['typo3/cms']['extension-key'])) {
                return $json['extra']['typo3/cms']['extension-key'];
            }

            if (isset($json['name'])) {
                [, $extensionKey] = explode('/', (string) $json['name'], 2);
                return str_replace('-', '_', $extensionKey);
            }
        } catch (Throwable $throwable) {
            return null;
        }

        return null;
    }
}
