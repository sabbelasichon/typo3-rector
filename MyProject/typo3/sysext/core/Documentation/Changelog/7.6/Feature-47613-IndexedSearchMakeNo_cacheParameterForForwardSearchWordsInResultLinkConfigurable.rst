
.. include:: /Includes.rst.txt

.. _feature-47613:

=========================================================================================================
Feature: #47613 - Indexed Search: make no_cache parameter for forwardSearchWordsInResultLink configurable
=========================================================================================================

See :issue:`47613`

Description
===========

A new TypoScript configuration option `forwardSearchWordsInResultLink.no_cache` has been added.
It controls whether the `no_cache` parameter should be added to page links together with search words.

Use following configuration for Indexed Search Extbase plugin:

.. code-block:: typoscript

   plugin.tx_indexedsearch.settings.forwardSearchWordsInResultLink.no_cache = 1

For plugin based on AbstractPlugin use:

.. code-block:: typoscript

   plugin.tx_indexedsearch.forwardSearchWordsInResultLink.no_cache = 1


Impact
======

The default value is set to 1, so it's backward compatible.


.. index:: TypoScript, ext:indexed_search
