<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\SysNote\Domain\Repository;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\SysNote\Domain\Repository\SysNoteRepository;

final class FindByPidsAndAuthorIdRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param $node Node|Node\Expr\MethodCall
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, SysNoteRepository::class)) {
            return null;
        }

        if (!$this->isName($node, 'findByPidsAndAuthor')) {
            return null;
        }

        $node->name = new Identifier('findByPidsAndAuthorId');

        $lastArgument = array_pop($node->args);

        $node->args[1] = $this->createMethodCall($lastArgument->value, 'getUid');

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use findByPidsAndAuthorId instead of findByPidsAndAuthor', [
            new CodeSample(
                <<<'PHP'
$sysNoteRepository = GeneralUtility::makeInstance(SysNoteRepository::class);
$backendUser = new BackendUser();
$sysNoteRepository->findByPidsAndAuthor('1,2,3', $backendUser);
PHP
                ,
                <<<'PHP'
$sysNoteRepository = GeneralUtility::makeInstance(SysNoteRepository::class);
$backendUser = new BackendUser();
$sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
PHP
            ),
        ]);
    }
}
