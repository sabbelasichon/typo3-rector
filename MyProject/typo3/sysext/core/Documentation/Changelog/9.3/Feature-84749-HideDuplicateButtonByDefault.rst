.. include:: /Includes.rst.txt

.. _feature-84749:

====================================================
Feature: #84749 - Hide "duplicate" button by default
====================================================

See :issue:`84749`

Description
===========

The "duplicate" button visibility can now be managed with userTsConfig using:

-  :typoscript:`options.showDuplicate = 1`
-  :typoscript:`options.showDuplicate.[table] = 1`


Impact
======

The button was only introduced in 9.0, but would with this change be hidden again.

.. index:: Backend, TSConfig, ext:backend
