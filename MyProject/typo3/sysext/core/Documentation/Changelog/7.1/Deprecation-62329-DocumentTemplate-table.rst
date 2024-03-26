
.. include:: /Includes.rst.txt

.. _deprecation-62329:

======================================================
Deprecation: #62329 - Deprecate DocumentTable::table()
======================================================

See :issue:`62329`

Description
===========

`DocumentTable::table()` has been marked as deprecated.


Impact
======

Calling `table()` of the `DocumentTable` class will trigger a deprecation log message.


Affected installations
======================

Instances which use `DocumentTable::table()` for rendering tables.


Migration
=========

Use fluid for rendering instead.


.. index:: PHP-API, Backend
