<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-88792-ForceTemplateParsingInTSFEAndTemplateService.html
 */
final class SubstituteDeprecatedRecordHistoryMethodsRector extends AbstractRector
{


    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch|Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node->var, \TYPO3\CMS\Backend\History\RecordHistory::class)
        ) {
            return null;
        }

        if ($this->isName($node->name, 'changeLog')) {
            $newNode = $this->createMethodCall(
                $node->var,
                'getChangelog');
            $this->addNodeBeforeNode($newNode, $node);
            $this->removeNode($node);
        }

        if ($this->isName($node->name, 'lastHistoryEntry')) {
            $newNode = $this->createMethodCall(
                $node->var,
                'getLastHistoryEntry');
            $this->addNodeBeforeNode($newNode, $node);
            $this->removeNode($node);
        }


        if ($this->isName($node->name, 'getHistoryEntry')) {
            try {
                $this->removeNode($node);
            } catch (ShouldNotHappenException $shouldNotHappenException) {
                $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
                $this->removeNode($parentNode);
            }
        }

        if ($this->isName($node->name, 'php:getHistoryData')) {
            try {
                $this->removeNode($node);
            } catch (ShouldNotHappenException $shouldNotHappenException) {
                $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
                $this->removeNode($parentNode);
            }
        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public
    function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Force template parsing in tsfe is replaced with context api and aspects',
            [
                new CodeSample(
                    <<<'PHP'
$recordHistory = \TYPO3\CMS\Core\Utility\GeneralUtility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\History\RecordHistory::class);
$historyEntry = $recordHistory->getHistoryEntry();
$historyData = $recordHistory->getHistoryData();
$shouldPerformRollback = $recordHistory->shouldPerformRollback();

$propChangelog = $recordHistory->changelog;
$propLastHistoryEntry = $recordHistory->lastHistoryEntry;

$historyChangelog = $recordHistory->createChangeLog();
$getElementData = $recordHistory->getElementData();
$recordHistory->performRollback();
$diff = $recordHistory->createMultipleDiff($data);
$recordHistory->setLastHistoryEntry(12345);
PHP
                    ,
                    <<<'PHP'
$recordHistory = \TYPO3\CMS\Core\Utility\GeneralUtility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\History\RecordHistory::class);




$propChangelog = $recordHistory->getChangeLog();
$propLastHistoryEntry = $recordHistory->getLastHistoryEntryNumber();

$historyChangelog = $recordHistory->getChangeLog();
$getElementData = $recordHistory->getElementInformation();
\TYPO3\CMS\Core\Utility\GeneralUtility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\History\RecordHistoryRollback\RecordHistoryRollback::class)->performRollback('!!!insertRollBackFieldsHere!!!',$recordHistory->getDiff($recordHistory->getChangeLog()));
$diff = $recordHistory->getDiff($data);
$recordHistory->setLastHistoryEntryNumber(12345);
PHP
                ),
            ]
        );
    }

}
