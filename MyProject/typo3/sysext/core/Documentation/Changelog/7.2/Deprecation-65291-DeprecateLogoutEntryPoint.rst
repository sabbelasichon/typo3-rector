
.. include:: /Includes.rst.txt

.. _deprecation-65283:

==================================================
Deprecation: #65283 - Deprecate logout entry point
==================================================

See :issue:`65283`

Description
===========

The following entry point has been marked as deprecated:

* typo3/logout.php


Impact
======

Using this entry point in a backend module will throw a deprecation message.


Migration
=========

Use `\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl()` instead with the according module name.

`typo3/logout.php` will have to be refactored to `\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl('logout')`


.. index:: PHP-API, Backend
