<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Migrations;

use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Return_;
use Rector\PhpParser\Parser\ParserFactory;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Migrations\Tca\TcaMigration;

final class TcaMigrationRector extends AbstractRector
{
    /**
     * @var ParserFactory
     */
    private $parserFactory;

    /**
     * @var TcaMigration
     */
    private $tcaMigration;

    public function __construct(ParserFactory $parserFactory, TcaMigration $tcaMigration)
    {
        $this->parserFactory = $parserFactory;
        $this->tcaMigration = $tcaMigration;
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @inheritDoc
     *
     * @throws ExportException
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node->expr instanceof Array_) {
            return null;
        }

        $items = $node->expr->items;

        // TODO: Is this guard clause enough to identify if it TCA-Configuration
        if ('ctrl' !== $this->getValue($items[0]->key) || 'columns' !== $this->getValue($items[1]->key)) {
            return null;
        }

        $ctrl = $items[0];

        $this->cleanCtrlSection($ctrl);

        $code = $this->createMigratedTca($node);

        $parser = $this->parserFactory->create();

        $stmts = $parser->parse($code);

        return $stmts[0];
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('This Rector migrates the TCA configuration for all configurations in separate files in folder TCA\Configuration. This is done on runtime via class \TYPO3\CMS\Core\Migrations\TcaMigration');
    }

    /**
     * @throws ExportException
     */
    private function createMigratedTca(Node $node): string
    {
        $returnStatement = new Return_($node->expr);

        $pathToFile = tempnam(sys_get_temp_dir(), 'tca');
        $this->printToFile($returnStatement, $pathToFile);

        $tca = include $pathToFile;

        $tcaMigrated = $this->tcaMigration->migrate(['table' => $tca]);
        $codeForAst = VarExporter::export($tcaMigrated['table'], VarExporter::ADD_RETURN);

        FileSystem::delete($pathToFile);

        return <<<CODE
<?php

$codeForAst;

CODE;
    }

    private function cleanCtrlSection(Node\Expr\ArrayItem $ctrl): void
    {
        if ($ctrl->value instanceof Array_) {
            $keepItems = [];
            foreach ($ctrl->value->items as $item) {
                if ('divider2tabs' === $this->getValue($item->key)) {
                    continue;
                }
                $keepItems[] = $item;
            }
            $ctrl->value->items = $keepItems;
        }
    }
}
