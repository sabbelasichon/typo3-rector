<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ConvertVersionStateToEnumRector\Fixture;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Versioning\VersionState;

class MyClass
{
    public function foo(): void
    {
        $type1 = VersionState::DEFAULT_STATE;
        $type2 = VersionState::NEW_PLACEHOLDER;
        $type3 = VersionState::DELETE_PLACEHOLDER;
        $type4 = VersionState::MOVE_POINTER;

        $someClass = VersionState::cast('DEFAULT_STATE');
        if (VersionState::cast('DEFAULT_STATE')->equals(VersionState::NEW_PLACEHOLDER)) {
            return;
        }

        $row['t3ver_oid'] = 1;
        $row['t3ver_state'] = 0;
        $versionState = VersionState::cast($row['t3ver_state']);
        if ($versionState->equals(VersionState::DELETE_PLACEHOLDER)) {
            // do something
        }

        if ($row['t3ver_oid'] > 0 || (int)($row['t3ver_state'] ?? 0) === VersionState::NEW_PLACEHOLDER) {
            // do something
        }

        $versionState = VersionState::cast($row['t3ver_state']);
        if ($versionState->equals(VersionState::DELETE_PLACEHOLDER)) {
            // do something
        }
    }

    protected function isDeletePlaceholder(): bool
    {
        $pageRecord['t3ver_state'] = 0;
        return VersionState::cast($pageRecord['t3ver_state'])->equals(VersionState::DELETE_PLACEHOLDER);
    }

    protected function negated(): bool
    {
        $result['t3ver_state'] = 0;
        return !VersionState::cast($result['t3ver_state'] ?? 0)->equals(VersionState::DELETE_PLACEHOLDER);
    }

    protected function negatedInIf(): bool
    {
        $row['t3ver_state'] = 0;
        $versionState = VersionState::cast($row['t3ver_state']);
        if (!$versionState->equals(VersionState::NEW_PLACEHOLDER)) {
            // do something
        }
    }

    private function special(): void
    {
        $pageRecord['t3ver_state'] = 0;
        if ((int)$pageRecord['t3ver_state'] === VersionState::MOVE_POINTER) {
            // do something
        }
    }

    private function specialFlipped(): void
    {
        $pageRecord['t3ver_state'] = 0;
        if (VersionState::MOVE_POINTER === (int)$pageRecord['t3ver_state']) {
            // do something
        }
    }

    private function switchStatement(): void
    {
        $row['t3ver_state'] = 1;
        switch (VersionState::cast($row['t3ver_state'])) {
            case new VersionState(VersionState::DELETE_PLACEHOLDER):
                $parts[] = 'Deleted element!';
                break;
            case new VersionState(VersionState::MOVE_POINTER):
                $parts[] = 'NEW LOCATION (Move-to Pointer) WSID#' . $row['t3ver_wsid'];
                break;
            case new VersionState(VersionState::NEW_PLACEHOLDER):
                $parts[] = 'New element!';
                break;
        }
    }

    private function castToString(): void
    {
        $fieldArray['t3ver_state'] = (string)new VersionState(VersionState::NEW_PLACEHOLDER);

        $delete = true;
        $overrideArray['t3ver_state'] = (string)($delete ? new VersionState(VersionState::DELETE_PLACEHOLDER) : new VersionState(VersionState::DEFAULT_STATE));
    }

    public function withNewAsAssignment(): void
    {
        $record['t3ver_state'] = 1;
        $versionState = new VersionState($record['t3ver_state']);
        if ($versionState->equals(VersionState::NEW_PLACEHOLDER) || (int)$record['t3ver_oid'] > 0) {
            // something
        }
    }

    public function withNewAsArgument(): void
    {
        $expressionBuilder = (new QueryBuilder())->expr();
        $expressionBuilder->lte(
            't3ver_state',
            new VersionState(VersionState::DEFAULT_STATE)
        );
    }

    public function inQueryBuilder(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder->createNamedParameter(VersionState::NEW_PLACEHOLDER, Connection::PARAM_INT);
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ConvertVersionStateToEnumRector\Fixture;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Versioning\VersionState;

class MyClass
{
    public function foo(): void
    {
        $type1 = VersionState::DEFAULT_STATE->value;
        $type2 = VersionState::NEW_PLACEHOLDER->value;
        $type3 = VersionState::DELETE_PLACEHOLDER->value;
        $type4 = VersionState::MOVE_POINTER->value;

        $someClass = VersionState::tryFrom('DEFAULT_STATE');
        if (VersionState::tryFrom('DEFAULT_STATE') === VersionState::NEW_PLACEHOLDER) {
            return;
        }

        $row['t3ver_oid'] = 1;
        $row['t3ver_state'] = 0;
        $versionState = VersionState::tryFrom($row['t3ver_state'] ?? 0);
        if ($versionState === VersionState::DELETE_PLACEHOLDER) {
            // do something
        }

        if ($row['t3ver_oid'] > 0 || VersionState::tryFrom($row['t3ver_state'] ?? 0) === VersionState::NEW_PLACEHOLDER) {
            // do something
        }

        $versionState = VersionState::tryFrom($row['t3ver_state'] ?? 0);
        if ($versionState === VersionState::DELETE_PLACEHOLDER) {
            // do something
        }
    }

    protected function isDeletePlaceholder(): bool
    {
        $pageRecord['t3ver_state'] = 0;
        return VersionState::tryFrom($pageRecord['t3ver_state'] ?? 0) === VersionState::DELETE_PLACEHOLDER;
    }

    protected function negated(): bool
    {
        $result['t3ver_state'] = 0;
        return VersionState::tryFrom($result['t3ver_state'] ?? 0) !== VersionState::DELETE_PLACEHOLDER;
    }

    protected function negatedInIf(): bool
    {
        $row['t3ver_state'] = 0;
        $versionState = VersionState::tryFrom($row['t3ver_state'] ?? 0);
        if ($versionState !== VersionState::NEW_PLACEHOLDER) {
            // do something
        }
    }

    private function special(): void
    {
        $pageRecord['t3ver_state'] = 0;
        if (VersionState::tryFrom($pageRecord['t3ver_state'] ?? 0) === VersionState::MOVE_POINTER) {
            // do something
        }
    }

    private function specialFlipped(): void
    {
        $pageRecord['t3ver_state'] = 0;
        if (VersionState::MOVE_POINTER === VersionState::tryFrom($pageRecord['t3ver_state'] ?? 0)) {
            // do something
        }
    }

    private function switchStatement(): void
    {
        $row['t3ver_state'] = 1;
        switch (VersionState::tryFrom($row['t3ver_state'] ?? 0)) {
            case VersionState::DELETE_PLACEHOLDER:
                $parts[] = 'Deleted element!';
                break;
            case VersionState::MOVE_POINTER:
                $parts[] = 'NEW LOCATION (Move-to Pointer) WSID#' . $row['t3ver_wsid'];
                break;
            case VersionState::NEW_PLACEHOLDER:
                $parts[] = 'New element!';
                break;
        }
    }

    private function castToString(): void
    {
        $fieldArray['t3ver_state'] = VersionState::NEW_PLACEHOLDER->value;

        $delete = true;
        $overrideArray['t3ver_state'] = $delete ? VersionState::DELETE_PLACEHOLDER->value : VersionState::DEFAULT_STATE->value;
    }

    public function withNewAsAssignment(): void
    {
        $record['t3ver_state'] = 1;
        $versionState = VersionState::tryFrom($record['t3ver_state'] ?? 0);
        if ($versionState === VersionState::NEW_PLACEHOLDER || (int)$record['t3ver_oid'] > 0) {
            // something
        }
    }

    public function withNewAsArgument(): void
    {
        $expressionBuilder = (new QueryBuilder())->expr();
        $expressionBuilder->lte(
            't3ver_state',
            VersionState::DEFAULT_STATE->value
        );
    }

    public function inQueryBuilder(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder->createNamedParameter(VersionState::NEW_PLACEHOLDER->value, Connection::PARAM_INT);
    }
}
?>
