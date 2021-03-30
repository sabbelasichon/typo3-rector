<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Parser\Printer;

use Helmich\TypoScriptParser\Parser\AST\Comment;
use Helmich\TypoScriptParser\Parser\AST\ConditionalStatement;
use Helmich\TypoScriptParser\Parser\AST\DirectoryIncludeStatement;
use Helmich\TypoScriptParser\Parser\AST\FileIncludeStatement;
use Helmich\TypoScriptParser\Parser\AST\IncludeStatement;
use Helmich\TypoScriptParser\Parser\AST\MultilineComment;
use Helmich\TypoScriptParser\Parser\AST\NestedAssignment;
use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Operator\BinaryObjectOperator;
use Helmich\TypoScriptParser\Parser\AST\Operator\Copy;
use Helmich\TypoScriptParser\Parser\AST\Operator\Delete;
use Helmich\TypoScriptParser\Parser\AST\Operator\Modification;
use Helmich\TypoScriptParser\Parser\AST\Operator\Reference;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Fixes the error https://github.com/martin-helmich/typo3-typoscript-parser/issues/52
 */
final class PrettyPrinter implements ASTPrinterInterface
{
    /**
     * @param Statement[]     $statements
     */
    public function printStatements(array $statements, OutputInterface $output): void
    {
        $this->printStatementList($statements, $output);
    }

    /**
     * @param Statement[]     $statements
     */
    private function printStatementList(array $statements, OutputInterface $output, int $nesting = 0): void
    {
        $indent = $this->getIndent($nesting);
        $count = count($statements);

        for ($i = 0; $i < $count; ++$i) {
            $statement = $statements[$i];

            if ($statement instanceof NestedAssignment) {
                $this->printNestedAssignment($output, $nesting, $statement);
            } elseif ($statement instanceof Assignment) {
                $this->printAssignment($output, $statement, $indent);
            } elseif ($statement instanceof BinaryObjectOperator) {
                $this->printBinaryObjectOperator($output, $statement, $nesting);
            } elseif ($statement instanceof Delete) {
                $output->writeln($indent . $statement->object->relativeName . ' >');
            } elseif ($statement instanceof Modification) {
                $output->writeln(
                    sprintf(
                        '%s%s := %s(%s)',
                        $indent,
                        $statement->object->relativeName,
                        $statement->call->method,
                        $statement->call->arguments
                    )
                );
            } elseif ($statement instanceof ConditionalStatement) {
                $next = $i + 1 < $count ? $statements[$i + 1] : null;
                $previous = $i - 1 >= 0 ? $statements[$i - 1] : null;

                $this->printConditionalStatement(
                    $output,
                    $nesting,
                    $statement,
                    $next instanceof ConditionalStatement,
                    $previous instanceof ConditionalStatement
                );
            } elseif ($statement instanceof IncludeStatement) {
                $this->printIncludeStatement($output, $statement);
            } elseif ($statement instanceof Comment) {
                $output->writeln($indent . $statement->comment);
            } elseif ($statement instanceof MultilineComment) {
                $output->writeln($indent . $statement->comment);
            }
        }
    }

    private function getIndent(int $nesting): string
    {
        return str_repeat('    ', $nesting);
    }

    private function printBinaryObjectOperator(
        OutputInterface $output,
        BinaryObjectOperator $operator,
        int $nesting
    ): void {
        $targetObjectPath = $operator->target->relativeName;

        if ($operator instanceof Copy) {
            $output->writeln($this->getIndent($nesting) . $operator->object->relativeName . ' < ' . $targetObjectPath);
        } elseif ($operator instanceof Reference) {
            $output->writeln($this->getIndent($nesting) . $operator->object->relativeName . ' =< ' . $targetObjectPath);
        }
    }

    private function printIncludeStatement(OutputInterface $output, IncludeStatement $statement): void
    {
        if ($statement instanceof FileIncludeStatement) {
            $this->printFileIncludeStatement($output, $statement);
        } elseif ($statement instanceof DirectoryIncludeStatement) {
            $this->printDirectoryIncludeStatement($output, $statement);
        }
    }

    private function printFileIncludeStatement(OutputInterface $output, FileIncludeStatement $statement): void
    {
        if ($statement->newSyntax) {
            $output->writeln("@import '" . $statement->filename . "'");
        } else {
            $attributes = '';

            if ($statement->condition) {
                $attributes = ' condition="' . $statement->condition . '"';
            }

            $output->writeln('<INCLUDE_TYPOSCRIPT: source="FILE:' . $statement->filename . '"' . $attributes . '>');
        }
    }

    private function printDirectoryIncludeStatement(OutputInterface $output, DirectoryIncludeStatement $statement): void
    {
        $attributes = '';

        if ($statement->extensions) {
            $attributes .= ' extensions="' . $statement->extensions . '"';
        }
        if ($statement->condition) {
            $attributes .= ' condition="' . $statement->condition . '"';
        }

        $includeStmt = '<INCLUDE_TYPOSCRIPT: source="DIR:' . $statement->directory . '"' . $attributes . '>';

        $output->writeln($includeStmt);
    }

    /**
     * @param int              $nesting
     */
    private function printNestedAssignment(OutputInterface $output, $nesting, NestedAssignment $statement): void
    {
        $output->writeln($this->getIndent($nesting) . $statement->object->relativeName . ' {');
        $this->printStatementList($statement->statements, $output, $nesting + 1);
        $output->writeln($this->getIndent($nesting) . '}');
    }

    private function printConditionalStatement(
        OutputInterface $output,
        int $nesting,
        ConditionalStatement $statement,
        bool $hasNext = false,
        bool $hasPrevious = false
    ): void {
        $output->writeln($statement->condition);
        $this->printStatementList($statement->ifStatements, $output, $nesting);

        if (count($statement->elseStatements) > 0) {
            $output->writeln('[else]');
            $this->printStatementList($statement->elseStatements, $output, $nesting);
        }

        $output->writeln('[global]');
    }

    private function printAssignment(OutputInterface $output, Assignment $statement, string $indent): void
    {
        if (false !== strpos($statement->value->value, "\n")) {
            $output->writeln($indent . $statement->object->relativeName . ' (');
            $output->writeln(rtrim($statement->value->value));
            $output->writeln($indent . ')');
            return;
        }

        $output->writeln($indent . $statement->object->relativeName . ' = ' . $statement->value->value);
    }
}
