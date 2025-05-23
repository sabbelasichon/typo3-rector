<?php

namespace TYPO3\CMS\Core\Versioning;

use TYPO3\CMS\Core\Type\Enumeration;

class VersionState extends Enumeration
{
    /**
     * The t3ver_state 0 is used for a live element, and any
     * commonly "modified" versioned record which is then identified
     * with t3ver_oid=uid of live ID
     */
    public const DEFAULT_STATE = 0;

    /**
     * If a new record is created in a workspace a new
     * record is added with t3ver_state = 1, a so-called
     * "newly versioned record", which acts as a standalone
     * record and has no t3ver_oid value. Publishing this record
     * is done by changing the t3ver_wsid field to "0".
     */
    public const NEW_PLACEHOLDER = 1;

    /**
     * Deleting elements is done by actually creating a
     * new version of the element and setting t3ver_state=2
     * that indicates the live element must be deleted upon
     * publishing the versions.
     */
    public const DELETE_PLACEHOLDER = 2;

    /**
     * When an element is moved to a different page, a versioned
     * record is created with t3ver_state=4 and the new PID.
     * When the database table has a sorting field, the sorting
     * on the versioned record is also updated to reflect the new position.
     *
     * When reading records from the DB with workspaces in mind,
     * the t3ver_state=4 records should be fetched as well to
     * find the new position and to do "workspace overlays" properly.
     */
    public const MOVE_POINTER = 4;
}
