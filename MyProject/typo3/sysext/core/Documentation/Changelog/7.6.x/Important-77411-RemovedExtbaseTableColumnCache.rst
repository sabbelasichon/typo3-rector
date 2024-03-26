
.. include:: /Includes.rst.txt

.. _important-77411:

======================================================
Important: #77411 - Removed extbase table column cache
======================================================

See :issue:`77411`

Description
===========

The extbase table column cache "extbase_typo3dbbackend_tablecolumns",
which was used to store all database fields of all database tables,
has been removed.

The associated configuration variable `$TYPO3_CONF_VARS[SYS][caching][cacheConfigurations][extbase_typo3dbbackend_tablecolumns]` can be removed.

.. index:: Database, ext:extbase, LocalConfiguration
