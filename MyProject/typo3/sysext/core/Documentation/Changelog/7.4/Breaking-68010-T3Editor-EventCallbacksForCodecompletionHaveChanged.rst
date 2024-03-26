
.. include:: /Includes.rst.txt

.. _breaking-68010-1668719172:

=============================================================================
Breaking: #68010 - T3Editor - Event callbacks for codecompletion have changed
=============================================================================

See :issue:`68010`

Description
===========

Due to the rewrite of T3Editor to jQuery, the event callbacks for codecompletion have changed.


Impact
======

Plugins for codecompletion written in Prototype will not work anymore.


Affected Installations
======================

Every third-party extension providing a T3Editor plugin extending the codecompletion.


Migration
=========

Port the plugin to an AMD module. The event callbacks are now part of the module object and not a standalone function anymore.

Example code:

.. code-block:: JavaScript

	CoolPlugin.afterKeyDown = function(currWordObj, compResult) {
		CoolPlugin.somethingFunky(currWordObj, compResult);
	};


.. index:: JavaScript, Backend, ext:t3editor
