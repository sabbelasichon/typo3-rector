.. include:: /Includes.rst.txt

.. _feature-86066:

============================================================
Feature: #86066 - CLI Commands for listing and showing sites
============================================================

See :issue:`86066`

Description
===========

Two new CLI commands have been added:

-  :bash:`site:list`
-  :bash:`site:show`

The list command can be executed via :bash:`typo3/sysext/core/bin/typo3 site:list` and will list all
configured sites with their configured Identifier, root page, base URL, languages, locales and
a flag whether or not the site is enabled.

The show command can be executed via :bash:`typo3/sysext/core/bin/typo3 site:show <identifier>`.
It needs an identifier of a configured site which must be provided after the command name.
The command will output the complete configuration for the site in the YAML syntax.


Impact
======

Reading access to the configured sites and their detailed configuration is now possible from CLI.

.. index:: CLI, ext:core
