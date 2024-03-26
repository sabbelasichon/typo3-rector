
.. include:: /Includes.rst.txt

.. _deprecation-76520:

======================================================================
Deprecation: #76520 - Deprecate method pages_getTree of PageLayoutView
======================================================================

See :issue:`76520`

Description
===========

The method :php:`pages_getTree()` of `PageLayoutView` has been marked as deprecated.


Impact
======

Calling the method :php:`pages_getTree` will trigger a deprecation log entry.


Affected Installations
======================

Any installation with a 3rd party extension calling the method in its PHP code.


Migration
=========

No migration available, implement the required functionality in your own code.

.. index:: PHP-API
