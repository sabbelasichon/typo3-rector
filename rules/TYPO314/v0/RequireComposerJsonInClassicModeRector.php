<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-108310-RequireComposerJsonInClassicMode.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RequireComposerJsonInClassicModeRector\RequireComposerJsonInClassicModeRectorTest
 */
final class RequireComposerJsonInClassicModeRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private FilesystemInterface $filesystem;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(FilesFinder $filesFinder, FilesystemInterface $filesystem, ValueResolver $valueResolver)
    {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Require composer.json in classic mode', [new CodeSample(
            <<<'CODE_SAMPLE'
$EM_CONF[$_EXTKEY] = [
    'title' => 'My Extension',
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$EM_CONF[$_EXTKEY] = [
    'title' => 'My Extension',
];

// composer.json is created in the extension directory
{
    "name": "vendor/extension",
    "type": "typo3-cms-extension",
    "extra": {
        "typo3/cms": {
            "extension-key": "extension"
        }
    }
}

CODE_SAMPLE
        )]);
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

        // Determine the extension root directory and key
        $directory = dirname($this->file->getFilePath());
        $composerJsonPath = $directory . '/composer.json';

        if ($this->filesystem->fileExists($composerJsonPath)) {
            return null;
        }

        /** @var ArrayDimFetch $arrayDimFetch */
        $arrayDimFetch = $node->var;
        if ($arrayDimFetch->dim instanceof String_) {
            $extensionKey = $this->valueResolver->getValue($arrayDimFetch->dim);
        } elseif ($this->isName($arrayDimFetch->dim, '_EXTKEY')) {
            $extensionKey = basename($directory);
        } else {
            // Something strange is happening here
            return null;
        }

        $title = null;
        $description = null;
        $authorName = null;
        $authorEmail = null;
        $typo3Constraint = null;
        $autoloadData = [];

        /** @var Array_ $emConfArray */
        $emConfArray = $node->expr;

        foreach ($emConfArray->items as $item) {
            if (! $item instanceof ArrayItem || ! $item->key instanceof Expr) {
                continue;
            }

            // Extract Title Data
            if ($this->valueResolver->isValue($item->key, 'title')) {
                $title = $this->valueResolver->getValue($item->value);
                continue;
            }

            // Extract Description Data
            if ($this->valueResolver->isValue($item->key, 'description')) {
                $description = $this->valueResolver->getValue($item->value);
                continue;
            }

            // Extract Author Data
            if ($this->valueResolver->isValue($item->key, 'author')) {
                $authorName = $this->valueResolver->getValue($item->value);
                continue;
            }

            if ($this->valueResolver->isValue($item->key, 'author_email')) {
                $authorEmail = $this->valueResolver->getValue($item->value);
                continue;
            }

            // Extract Autoload Data
            if ($item->value instanceof Array_ && $this->valueResolver->isValue($item->key, 'autoload')) {
                $autoloadArray = $item->value;
                $extractedAutoload = [];

                // Iterate over autoload types (e.g., 'classmap', 'psr-4')
                foreach ($autoloadArray->items as $autoloadItem) {
                    if (! $autoloadItem instanceof ArrayItem || ! $autoloadItem->key instanceof Expr) {
                        continue;
                    }

                    $autoloadType = $this->valueResolver->getValue($autoloadItem->key);
                    $extractedValue = $this->valueResolver->getValue($autoloadItem->value);

                    if ($autoloadType !== null && is_array($extractedValue)) {
                        $extractedAutoload[(string) $autoloadType] = $extractedValue;
                    }
                }

                $autoloadData = $extractedAutoload;
                continue;
            }

            // Extract Constraints Data
            if ($item->value instanceof Array_ && $this->valueResolver->isValue($item->key, 'constraints')) {
                $constraintsArray = $item->value;

                foreach ($constraintsArray->items as $constraintItem) {
                    if (! $constraintItem instanceof ArrayItem || ! $constraintItem->key instanceof Expr) {
                        continue;
                    }

                    if ($constraintItem->value instanceof Array_
                        && $this->valueResolver->isValue($constraintItem->key, 'depends')
                    ) {
                        $dependsArray = $constraintItem->value;

                        foreach ($dependsArray->items as $dependency) {
                            if (! $dependency instanceof ArrayItem || ! $dependency->key instanceof Expr) {
                                continue;
                            }

                            if ($this->valueResolver->isValue($dependency->key, 'typo3')) {
                                $typo3Constraint = $this->valueResolver->getValue($dependency->value);
                                // Found the constraint, no need to check further depends items
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        $composerRequire = [];
        if (is_string($typo3Constraint) && $typo3Constraint !== '') {
            // Split by '-' to get version range (e.g., '12.4.3-12.4.99')
            $parts = explode('-', $typo3Constraint);

            // The first part is the minimum version (e.g., '12.4.3').
            $minVersion = $parts[0];

            // Extract major and minor version (e.g., '12.4')
            if (preg_match('/^(\d+\.\d+)/', $minVersion, $versionMatch)) {
                $majorMinor = $versionMatch[1];
                // Format for composer: ^12.4
                $composerRequire['typo3/cms-core'] = '^' . $majorMinor;
            }
        }

        $vendorName = 'vendor';
        $packageName = $vendorName . '/' . str_replace('_', '-', $extensionKey);

        $composerData = [
            'name' => $packageName,
        ];

        $finalDescription = null;
        if ($title !== null && $description !== null) {
            // Combine title and description: "Title - Description"
            $finalDescription = sprintf('%s - %s', $title, $description);
        } elseif ($description !== null) {
            // Fallback: Use only description if title is missing
            $finalDescription = (string) $description;
        } elseif ($title !== null) {
            // Fallback: Use only title if description is missing
            $finalDescription = (string) $title;
        }

        if ($finalDescription !== null) {
            $composerData['description'] = $finalDescription;
        }

        $composerData['type'] = 'typo3-cms-extension';

        if ($authorName !== null) {
            $authorEntry = [
                'name' => (string) $authorName,
            ];
            if ($authorEmail !== null) {
                $authorEntry['email'] = (string) $authorEmail;
            }

            $composerData['authors'] = [$authorEntry];
        }

        if ($composerRequire !== []) {
            $composerData['require'] = $composerRequire;
        }

        if ($autoloadData !== []) {
            $composerData['autoload'] = $autoloadData;
        }

        $composerData['extra'] = [
            'typo3/cms' => [
                'extension-key' => $extensionKey,
            ],
        ];

        $jsonContent = json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $this->filesystem->write($composerJsonPath, $jsonContent . PHP_EOL);

        return null;
    }

    private function shouldSkip(Assign $node): bool
    {
        if (! $this->filesFinder->isExtEmConf($this->file->getFilePath())) {
            return true;
        }

        if (! $node->var instanceof ArrayDimFetch) {
            return true;
        }

        if (! $node->expr instanceof Array_) {
            return true;
        }

        return ! $this->isName($node->var->var, 'EM_CONF');
    }
}
