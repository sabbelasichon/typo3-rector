
.. include:: /Includes.rst.txt

.. _feature-73461-1668719172:

==========================================================
Feature: #73461 - Enable import module for non admin users
==========================================================

See :issue:`73461`

Description
===========

The new userTsConfig option :typoscript:`options.impexp.enableImportForNonAdminUser` can be used to enable
the import module of EXT:impexp for non admin users.


Impact
======

This option should be enabled for "trustworthy" backend users only.

.. index:: TSConfig, Backend, ext:impexp
