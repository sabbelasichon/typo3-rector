<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Important-82692-GuidelinesForExtensionFiles.html
 */
final class ReplaceExtKeyWithExtensionKeyRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace $_EXTKEY with extension key', [new CodeSample(<<<'PHP'
ExtensionUtility::configurePlugin(
    'Foo.'.$_EXTKEY,
    'ArticleTeaser',
    [
        'FooBar' => 'baz',
    ]
);
PHP
                , <<<'PHP'
ExtensionUtility::configurePlugin(
    'Foo.'.'bar',
    'ArticleTeaser',
    [
        'FooBar' => 'baz',
    ]
);
PHP
            )]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactor(Node $node): ?Node
    {
        /** @var SmartFileInfo $fileInfo */
        $fileInfo = $node->getAttribute(AttributeKey::FILE_INFO);

        if (! $fileInfo instanceof SmartFileInfo) {
            return null;
        }

        if (! $this->isExtLocalConf($fileInfo) && ! $this->isExtTables($fileInfo)) {
            return null;
        }

        if (! $this->isExtensionKeyVariable($node)) {
            return null;
        }

        return new String_($this->createExtensionKeyFromFolder($node));
    }

    private function isExtLocalConf(SmartFileInfo $fileInfo): bool
    {
        return Strings::endsWith($fileInfo->getFilename(), 'ext_localconf.php');
    }

    private function isExtTables(SmartFileInfo $fileInfo): bool
    {
        return Strings::endsWith($fileInfo->getFilename(), 'ext_tables.php');
    }

    private function isExtensionKeyVariable(Variable $variable): bool
    {
        return $this->isName($variable, '_EXTKEY');
    }

    private function createExtensionKeyFromFolder(Node $node): string
    {
        /** @var SmartFileInfo $fileInfo */
        $fileInfo = $node->getAttribute(AttributeKey::FILE_INFO);

        return basename($fileInfo->getPath());
    }
}
