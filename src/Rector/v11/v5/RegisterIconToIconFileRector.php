<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v5;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Rector\Core\PhpParser\Parser\SimplePhpParser;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Ssch\TYPO3Rector\Rector\v11\v5\RegisterIconToIconFileRector\AddIconsToReturnRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Icon/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\RegisterIconToIconFileRector\RegisterIconToIconFileRectorTest
 */
final class RegisterIconToIconFileRector extends AbstractRector
{
    /**
     * @var string
     */
    private const REMOVE_EMPTY_LINES = '/^[ \t]*[\r\n]+/m';

    public function __construct(
        private FilesFinder $filesFinder,
        private AddIconsToReturnRector $addIconsToReturnRector,
        private SimplePhpParser $simplePhpParser,
        private NodePrinterInterface $nodePrinter
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Imaging\IconRegistry')
        )) {
            return null;
        }

        if (! $this->nodeNameResolver->isName($node->name, 'registerIcon')) {
            return null;
        }

        $currentSmartFileInfo = $this->file->getSmartFileInfo();

        $extEmConfFileInfo = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($currentSmartFileInfo);

        if (! $extEmConfFileInfo instanceof SmartFileInfo) {
            return null;
        }

        $extensionDirectory = dirname($extEmConfFileInfo->getRealPath());

        $iconsFilePath = sprintf('%s/Configuration/Icons.php', $extensionDirectory);

        $identifier = $this->valueResolver->getValue($node->args[0]->value);

        if (! is_string($identifier)) {
            return null;
        }

        $options = $this->valueResolver->getValue($node->args[2]->value);

        $iconConfiguration = [
            'provider' => $node->args[1]->value,
        ];

        if (is_array($options)) {
            $iconConfiguration = array_merge($iconConfiguration, $options);
        }

        $this->addNewIconToIconsFile($iconsFilePath, $identifier, $iconConfiguration);

        $this->removeNode($node);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Generate or add registerIcon calls to Icons.php file', [new CodeSample(
            <<<'CODE_SAMPLE'

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerIcon(
    'mybitmapicon',
    BitmapIconProvider::class,
    [
        'source' => 'EXT:my_extension/Resources/Public/Icons/mybitmap.png',
    ]
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

// Add Icons.php file
CODE_SAMPLE
        )]);
    }

    /**
     * @param Stmt[] $stmts
     */
    private function decorateNamesToFullyQualified(array $stmts): void
    {
        // decorate nodes with names first
        $nameResolverNodeTraverser = new NodeTraverser();
        $nameResolverNodeTraverser->addVisitor(new NameResolver());
        $nameResolverNodeTraverser->traverse($stmts);
    }

    /**
     * @param array<string, mixed> $iconConfiguration
     */
    private function addNewIconToIconsFile(
        string $iconsFilePath,
        string $iconIdentifier,
        array $iconConfiguration
    ): void {
        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        $existingIcons = null;
        foreach ($addedFilesWithContent as $addedFileWithContent) {
            if ($addedFileWithContent->getFilePath() === $iconsFilePath) {
                $existingIcons = $addedFileWithContent->getFileContent();
            }
        }

        if (is_string($existingIcons)) {
            $stmts = $this->simplePhpParser->parseString($existingIcons);
        } else {
            $stmts = [new Return_($this->nodeFactory->createArray([]))];
        }

        $this->decorateNamesToFullyQualified($stmts);

        $nodeTraverser = new NodeTraverser();
        $this->addIconsToReturnRector->configure([
            AddIconsToReturnRector::ICON_IDENTIFIER => $iconIdentifier,
            AddIconsToReturnRector::ICON_CONFIGURATION => $iconConfiguration,
        ]);
        $nodeTraverser->addVisitor($this->addIconsToReturnRector);
        $stmts = $nodeTraverser->traverse($stmts);

        /** @var Stmt[] $stmts */
        $changedIconsContent = $this->nodePrinter->prettyPrintFile($stmts);

        $changedIconsContent = Strings::replace($changedIconsContent, self::REMOVE_EMPTY_LINES);

        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent($iconsFilePath, $changedIconsContent)
        );
    }
}
