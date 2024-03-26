
.. include:: /Includes.rst.txt

.. _deprecation-71153:

================================================
Deprecation: #71153 - DocumentTemplate->spacer()
================================================

See :issue:`71153`

Description
===========

Method `TYPO3\CMS\Backend\Template\DocumentTemplate::spacer()` has been marked as deprecated.


Affected Installations
======================

Instances with custom backend modules that use this method.


Migration
=========

Add the needed margin as HTML / CSS.


.. index:: PHP-API, Backend
