
.. include:: /Includes.rst.txt

.. _breaking-72418:

=========================================================
Breaking: #72418 - Deprecated backend-related PHP classes
=========================================================

See :issue:`72418`

Description
===========

The following PHP classes have been removed:

* `TYPO3\CMS\Backend\Module\ModuleController`
* `TYPO3\CMS\Backend\Module\ModuleSettings`
* `TYPO3\CMS\Backend\View\LogoView`
* `TYPO3\CMS\Backend\View\ModuleMenuView`
* `TYPO3\CMS\Backend\View\PageLayout\ExtDirect\ExtdirectPageCommands`
* `TYPO3\CMS\Backend\View\ThumbnailView`


Impact
======

Calling any of these PHP classes directly will result in a fatal error.


Affected Installations
======================

Any installation with a custom PHP code accessing these PHP classes.

.. index:: PHP-API, Backend
