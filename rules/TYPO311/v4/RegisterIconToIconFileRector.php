<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v4;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeVisitor;
use PHPStan\Parser\ParserErrorsException;
use PHPStan\Type\ObjectType;
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\Parser\RectorParser;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\PhpParser\Printer\PrettyTypo3Printer;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.4/Feature-94692-RegisteringIconsViaServiceContainer.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v4\RegisterIconToIconFileRector\RegisterIconToIconFileRectorTest
 */
final class RegisterIconToIconFileRector extends AbstractRector implements DocumentedRuleInterface
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

    /**
     * @readonly
     */
    private PrettyTypo3Printer $prettyTypo3Printer;

    /**
     * @readonly
     */
    private RectorParser $rectorParser;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ValueResolver $valueResolver,
        RectorParser $rectorParser,
        PrettyTypo3Printer $prettyTypo3Printer
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
        $this->rectorParser = $rectorParser;
        $this->prettyTypo3Printer = $prettyTypo3Printer;
    }

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
return [
    'mybitmapicon' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        'source' => 'EXT:my_extension/Resources/Public/Icons/mybitmap.png',
    ],
];
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @throws ShouldNotHappenException
     */
    public function refactor(Node $node): ?int
    {
        $methodCall = $node->expr;
        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($methodCall)) {
            return null;
        }

        [$iconIdentifierString, $innerItems] = $this->createNewIconArray($methodCall);

        $directoryName = dirname($this->getFile()->getFilePath());
        $iconsFilePath = $directoryName . '/Configuration/Icons.php';

        $newContent = $this->addNewIconToIconsFile($iconsFilePath, $iconIdentifierString, $innerItems);
        if ($newContent === null) {
            return null;
        }

        $this->filesystem->write($iconsFilePath, $newContent);

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        $args = $methodCall->getArgs();
        if (count($args) < 3) {
            return true;
        }

        if (! $this->isObjectType($methodCall->var, new ObjectType('TYPO3\CMS\Core\Imaging\IconRegistry'))) {
            return true;
        }

        if (! $this->isName($methodCall->name, 'registerIcon')) {
            return true;
        }

        return ! $this->filesFinder->isExtLocalConf($this->getFile()->getFilePath());
    }

    /**
     * @return array<int, mixed>
     */
    private function createNewIconArray(MethodCall $methodCall): array
    {
        $args = $methodCall->getArgs();

        $iconIdentifier = $args[0]->value;
        $iconProvider = $args[1]->value;
        $options = $args[2]->value;

        $iconIdentifierString = $this->valueResolver->getValue($iconIdentifier);
        $iconProviderString = $this->valueResolver->getValue($iconProvider);

        $innerItems = [];
        $innerItems['provider'] = $iconProviderString;

        $optionsValue = $this->valueResolver->getValue($options);
        if (is_array($optionsValue)) {
            foreach ($optionsValue as $key => $value) {
                $innerItems[$key] = $value;
            }
        }

        return [$iconIdentifierString, $innerItems];
    }

    /**
     * @param array<string, mixed> $iconConfiguration
     * @throws ShouldNotHappenException
     */
    private function addNewIconToIconsFile(
        string $iconsFilePath,
        string $iconIdentifier,
        array $iconConfiguration
    ): ?string {
        if ($this->filesystem->fileExists($iconsFilePath)) {
            $existingIcons = $this->filesystem->read($iconsFilePath);
            $nodes = $this->parseIconFileNodes($existingIcons);
            if ($nodes === null) {
                return null;
            }
        } else {
            $return = new Return_($this->nodeFactory->createArray([]));
            $nodes = [$return];
        }

        $returnArray = $this->findReturnedArray($nodes);
        if (! $returnArray instanceof Array_) {
            return null;
        }

        $returnArray->items[] = new ArrayItem(
            $this->nodeFactory->createArray($this->createIconOptionsWithNodes($iconConfiguration)),
            new String_($iconIdentifier),
            false
        );

        return $this->prettyTypo3Printer->prettyPrintFile($nodes);
    }

    /**
     * @return Node\Stmt[]|null
     */
    private function parseIconFileNodes(string $fileContent): ?array
    {
        try {
            return $this->rectorParser->parseFileContentToStmtsAndTokens($fileContent)
                ->getStmts();
        } catch (ParserErrorsException $parserErrorsException) {
            try {
                return $this->rectorParser->parseFileContentToStmtsAndTokens($fileContent, false)
                    ->getStmts();
            } catch (\Throwable $throwable) {
                return null;
            }
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * @param Node\Stmt[] $nodes
     */
    private function findReturnedArray(array $nodes): ?Array_
    {
        foreach ($nodes as $node) {
            if (! $node instanceof Return_) {
                continue;
            }

            if ($node->expr instanceof Array_) {
                return $node->expr;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $iconConfiguration
     * @return array<string, mixed>
     */
    private function createIconOptionsWithNodes(array $iconConfiguration): array
    {
        $optionsWithNodes = [];

        foreach ($iconConfiguration as $key => $value) {
            if ($key === 'provider' && is_string($value)) {
                $optionsWithNodes[$key] = $this->nodeFactory->createClassConstReference($value);
                continue;
            }

            $optionsWithNodes[$key] = $value;
        }

        return $optionsWithNodes;
    }
}
