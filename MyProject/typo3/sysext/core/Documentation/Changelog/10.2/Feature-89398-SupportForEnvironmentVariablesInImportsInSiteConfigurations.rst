.. include:: /Includes.rst.txt

.. _feature-89398:

=====================================================================================
Feature: #89398 - Support for environment variables in imports in site configurations
=====================================================================================

See :issue:`89398`

Description
===========

Environment variables are now resolved in imports of site configuration YAML files.

Example:

.. code-block:: yaml

   imports:
     -
       resource: 'Env_%env("foo")%.yaml'


Impact
======

It is now possible to use environment variables in imports of site configuration yaml files.

.. index:: Backend, ext:backend
