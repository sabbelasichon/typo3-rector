<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Collector;

use Helmich\TypoScriptParser\Parser\AST\Statement;
use Rector\Core\ValueObject\Application\File;

final class RemoveTypoScriptStatementCollector
{
    /**
     * @var Statement[][]
     */
    private array $statementsToBeRemoved = [];

    public function removeStatement(Statement $statement, File $file): void
    {
        $filePath = $file->getFilePath();

        if (! isset($this->statementsToBeRemoved[$filePath])) {
            $this->statementsToBeRemoved[$filePath] = [];
        }

        $this->statementsToBeRemoved[$filePath][$statement->sourceLine] = $statement;
    }

    public function shouldStatementBeRemoved(Statement $originalStatement, File $file): bool
    {
        if (! isset($this->statementsToBeRemoved[$file->getFilePath()])) {
            return false;
        }
        return array_key_exists($originalStatement->sourceLine, $this->statementsToBeRemoved[$file->getFilePath()]);
    }

    public function reset(): void
    {
        $this->statementsToBeRemoved = [];
    }
}
