<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Database\Query\Restriction;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Versioning\VersionState;

/**
 * Restriction to make queries in TYPO3 backend context versioning/ workspace aware
 * @deprecated will be removed in TYPO3 v13.0. Use WorkspaceRestriction instead.
 */
class BackendWorkspaceRestriction implements QueryRestrictionInterface
{
    /**
     * @var int
     */
    protected $workspaceId;

    /**
     * @var bool
     */
    protected $includeRowsForWorkspaceOverlay;

    /**
     * @param bool $includeRowsForWorkspaceOverlay
     */
    public function __construct(int $workspaceId = null, $includeRowsForWorkspaceOverlay = true)
    {
        trigger_error('BackendWorkspaceRestriction will be removed in TYPO3 v13.0. Use WorkspaceRestriction instead.', E_USER_DEPRECATED);
        if ($workspaceId === null) {
            $this->workspaceId = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id');
        } else {
            $this->workspaceId = $workspaceId;
        }
        $this->includeRowsForWorkspaceOverlay = $includeRowsForWorkspaceOverlay;
    }

    /**
     * Main method to build expressions for given tables
     *
     * @param array $queriedTables Array of tables, where array key is table alias and value is a table name
     * @param ExpressionBuilder $expressionBuilder Expression builder instance to add restrictions with
     * @return CompositeExpression The result of query builder expression(s)
     */
    public function buildExpression(array $queriedTables, ExpressionBuilder $expressionBuilder): CompositeExpression
    {
        $constraints = [];
        foreach ($queriedTables as $tableAlias => $tableName) {
            $workspaceEnabled = $GLOBALS['TCA'][$tableName]['ctrl']['versioningWS'] ?? null;
            if (!empty($workspaceEnabled)) {
                $workspaceIdExpression = $expressionBuilder->eq(
                    $tableAlias . '.t3ver_wsid',
                    (int)$this->workspaceId
                );
                if ($this->includeRowsForWorkspaceOverlay) {
                    $constraints[] = $expressionBuilder->or(
                        $workspaceIdExpression,
                        $expressionBuilder->lte(
                            $tableAlias . '.t3ver_state',
                            // Trigger __toString(), then cast int
                            (int)(string)new VersionState(VersionState::DEFAULT_STATE)
                        )
                    );
                } else {
                    $comparisonExpression = $this->workspaceId === 0 ? 'eq' : 'gt';
                    $constraints[] = $workspaceIdExpression;
                    $constraints[] = $expressionBuilder->{$comparisonExpression}(
                        $tableAlias . '.t3ver_oid',
                        0
                    );
                }
            }
        }
        return $expressionBuilder->and(...$constraints);
    }
}
