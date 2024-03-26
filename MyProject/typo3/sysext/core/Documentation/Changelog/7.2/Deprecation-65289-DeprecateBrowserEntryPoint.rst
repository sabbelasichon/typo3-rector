
.. include:: /Includes.rst.txt

.. _deprecation-65289:

===================================================
Deprecation: #65289 - Deprecate browser entry point
===================================================

See :issue:`65289`

Description
===========

The following entry point has been marked as deprecated:

* typo3/browser.php


Impact
======

Using this entry point in a backend module will throw a deprecation message.


Migration
=========

Use `\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl()` instead with the according module name.

`typo3/browser.php` will have to be refactored to `\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl('browser')`


.. index:: PHP-API, Backend
