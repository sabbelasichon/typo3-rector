
.. include:: /Includes.rst.txt

.. _deprecation-65293:

=================================================================
Deprecation: #65293 - Deprecate file navigation frame entry point
=================================================================

See :issue:`65293`

Description
===========

The following entry point has been marked as deprecated:

* typo3/alt_file_navframe.php


Impact
======

Using this entry point in a backend module will throw a deprecation message.


Migration
=========

Use `\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl()` instead with the according module name.

`typo3/alt_file_navframe.php` will have to be refactored to `\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl('file_navframe')`


.. index:: PHP-API, Backend
