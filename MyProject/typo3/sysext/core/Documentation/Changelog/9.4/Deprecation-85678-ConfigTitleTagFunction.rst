.. include:: /Includes.rst.txt

.. _deprecation-85678-1668719172:

=============================================
Deprecation: #85678 - config.titleTagFunction
=============================================

See :issue:`85678`

Description
===========

The TypoScript option :typoscript:`config.titleTagFunction` has been marked as deprecated and will be removed with TYPO3 v10.


Impact
======

Installations using the option will trigger a PHP :php:`E_USER_DEPRECATED` error.


Affected Installations
======================

Instances using the option.


Migration
=========

Please use the new PageTitle API to alter the title tag.

.. index:: TypoScript, NotScanned
