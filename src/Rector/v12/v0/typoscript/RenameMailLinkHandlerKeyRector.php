<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typoscript;

use Helmich\TypoScriptParser\Parser\AST\NestedAssignment;
use Helmich\TypoScriptParser\Parser\AST\ObjectPath;
use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Important-97159-MailLinkHandlerKeyInTSconfigRenamed.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typoscript\RenameMailLinkHandlerKeyRector\RenameMailLinkHandlerKeyRectorTest
 */
final class RenameMailLinkHandlerKeyRector extends AbstractTypoScriptRector
{
    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof NestedAssignment && ! $statement instanceof Assignment) {
            return;
        }

        if (! $statement->object instanceof ObjectPath) {
            return;
        }

        if ($statement->object->absoluteName !== 'TCEMAIN.linkHandler.mail') {
            return;
        }

        $statement->object->relativeName = 'email';
        $statement->object->absoluteName = 'TCEMAIN.linkHandler.email';

        $this->hasChanged = true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename key mail to email for MailLinkHandler', [new CodeSample(
            <<<'CODE_SAMPLE'
TCEMAIN.linkHandler {
    mail {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\MailLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:email
        displayAfter = page,file,folder,url
        scanBefore = url
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
TCEMAIN.linkHandler {
    email {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\MailLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:email
        displayAfter = page,file,folder,url
        scanBefore = url
    }
}
CODE_SAMPLE
        )]);
    }
}
