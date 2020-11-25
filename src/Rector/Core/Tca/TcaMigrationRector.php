<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Tca;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\ParserFactory;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Tca\Refactorings\TcaMigrationRefactoring;
use Symfony\Component\VarExporter\Exception\ExceptionInterface;
use Symfony\Component\VarExporter\VarExporter;
use Webmozart\Assert\Assert;

final class TcaMigrationRector extends AbstractRector
{
    /**
     * @var ParserFactory
     */
    private $parserFactory;

    /**
     * @var TcaMigrationRefactoring[]
     */
    private $tcaMigrations;

    public function __construct(ParserFactory $parserFactory, array $tcaMigrations)
    {
        Assert::allIsInstanceOf($tcaMigrations, TcaMigrationRefactoring::class);
        $this->parserFactory = $parserFactory;
        $this->tcaMigrations = $tcaMigrations;
    }

    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     *
     * @throws ExceptionInterface
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof Array_) {
            return null;
        }

        $items = $node->expr->items;

        $ctrl = null;
        $columns = null;

        foreach ($items as $item) {
            $itemKey = (string) $this->getValue($item->key);
            if ('ctrl' === $itemKey) {
                $ctrl = $item;
            } elseif ('columns' === $itemKey) {
                $columns = $item;
            }
        }

        // TODO: Is this guard clause enough to identify if it TCA-Configuration
        if (null === $ctrl || null === $columns) {
            return null;
        }

        $code = $this->createMigratedTca($node);

        $parser = $this->parserFactory->create(ParserFactory::PREFER_PHP7);

        $stmts = $parser->parse($code);

        if (null === $stmts) {
            return null;
        }

        return array_shift($stmts);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'This Rector migrates the TCA configuration for all configurations in separate files in folder TCA\Configuration. This is done on runtime via core migration classes \TYPO3\CMS\Core\Migrations\TcaMigration for different versions'
        );
    }

    /**
     * @throws ExceptionInterface
     */
    private function createMigratedTca(Return_ $node): string
    {
        $returnStatement = new Return_($node->expr);

        $pathToFile = (string) tempnam(sys_get_temp_dir(), 'tca');
        $this->printToFile([$returnStatement], $pathToFile);

        $tca = include $pathToFile;

        $tcaMigrated = $tca;
        if (is_iterable($this->tcaMigrations)) {
            foreach ($this->tcaMigrations as $tcaMigration) {
                $tcaMigrated = $tcaMigration->migrate($tcaMigrated);
            }
        }

        $codeForAst = 'return ' . VarExporter::export($tcaMigrated['table']) . ';' . PHP_EOL;

        FileSystem::delete($pathToFile);

        return sprintf('<?php
%s;', $codeForAst);
    }
}
