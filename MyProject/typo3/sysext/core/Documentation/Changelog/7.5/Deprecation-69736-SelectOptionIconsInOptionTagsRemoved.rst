
.. include:: /Includes.rst.txt

.. _deprecation-69736:

=============================================================
Deprecation: #69736 - Select option iconsInOptionTags removed
=============================================================

See :issue:`69736`

Description
===========

The option `iconsInOptionTags` of TCA `select` fields has been removed due
to little support in browsers.


Impact
======

The usage of this option triggers a deprecation log entry and is automatically
removed in TCA tables during bootstrap.


Affected Installations
======================

Any `TCA` configuration using `iconsInOptionTags`.


Migration
=========

Remove usage of this option.


.. index:: TCA, Backend
