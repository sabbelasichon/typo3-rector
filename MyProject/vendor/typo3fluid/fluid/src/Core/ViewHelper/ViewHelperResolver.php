<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\Core\Parser\Exception as ParserException;
use TYPO3Fluid\Fluid\Core\Parser\Patterns;

/**
 * Class ViewHelperResolver
 *
 * Responsible for resolving instances of ViewHelpers and for
 * interacting with ViewHelpers; to translate ViewHelper names
 * into actual class names and resolve their ArgumentDefinitions.
 *
 * Replacing this class in for example a framework allows that
 * framework to be responsible for creating ViewHelper instances
 * and detecting possible arguments.
 */
class ViewHelperResolver
{
    /**
     * @var array
     */
    protected $resolvedViewHelperClassNames = [];

    /**
     * Namespaces requested by the template being rendered,
     * in [shortname => phpnamespace] format.
     *
     * @var array
     */
    protected $namespaces = [
        'f' => ['TYPO3Fluid\\Fluid\\ViewHelpers']
    ];

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Add a PHP namespace where ViewHelpers can be found and give
     * it an alias/identifier.
     *
     * The provided namespace can be either a single namespace or
     * an array of namespaces, as strings. The identifier/alias is
     * always a single, alpha-numeric ASCII string.
     *
     * Calling this method multiple times with different PHP namespaces
     * for the same alias causes that namespace to be *extended*,
     * meaning that the PHP namespace you provide second, third etc.
     * are also used in lookups and are used *first*, so that if any
     * of the namespaces you add contains a class placed and named the
     * same way as one that exists in an earlier namespace, then your
     * class gets used instead of the earlier one.
     *
     * Example:
     *
     * $resolver->addNamespace('my', 'My\Package\ViewHelpers');
     * // Any ViewHelpers under this namespace can now be accessed using for example {my:example()}
     * // Now, assuming you also have an ExampleViewHelper class in a different
     * // namespace and wish to make that ExampleViewHelper override the other:
     * $resolver->addNamespace('my', 'My\OtherPackage\ViewHelpers');
     * // Now, since ExampleViewHelper exists in both places but the
     * // My\OtherPackage\ViewHelpers namespace was added *last*, Fluid
     * // will find and use My\OtherPackage\ViewHelpers\ExampleViewHelper.
     *
     * Alternatively, setNamespaces() can be used to reset and redefine
     * all previously added namespaces - which is great for cases where
     * you need to remove or replace previously added namespaces. Be aware
     * that setNamespaces() also removes the default "f" namespace, so
     * when you use this method you should always include the "f" namespace.
     *
     * @param string $identifier
     * @param string|array $phpNamespace
     */
    public function addNamespace($identifier, $phpNamespace)
    {
        if (!array_key_exists($identifier, $this->namespaces) || $this->namespaces[$identifier] === null) {
            $this->namespaces[$identifier] = $phpNamespace === null ? null : (array)$phpNamespace;
        } elseif (is_array($phpNamespace)) {
            $this->namespaces[$identifier] = array_unique(array_merge($this->namespaces[$identifier], $phpNamespace));
        } elseif (isset($this->namespaces[$identifier]) && !in_array($phpNamespace, $this->namespaces[$identifier])) {
            $this->namespaces[$identifier][] = $phpNamespace;
        }
    }

    /**
     * Wrapper to allow adding namespaces in bulk *without* first
     * clearing the already added namespaces. Utility method mainly
     * used in compiled templates, where some namespaces can be added
     * from outside and some can be added from compiled values.
     *
     * @param array $namespaces
     */
    public function addNamespaces(array $namespaces)
    {
        foreach ($namespaces as $identifier => $namespace) {
            $this->addNamespace($identifier, $namespace);
        }
    }

    /**
     * Resolves the PHP namespace based on the Fluid xmlns namespace,
     * which can be either a URL matching the Patterns::NAMESPACEPREFIX
     * and Patterns::NAMESPACESUFFIX rules, or a PHP namespace. When
     * namespace is a PHP namespace it is optional to suffix it with
     * the "\ViewHelpers" segment, e.g. "My\Package" is as valid to
     * use as "My\Package\ViewHelpers" is.
     *
     * @param string $fluidNamespace
     * @return string
     */
    public function resolvePhpNamespaceFromFluidNamespace($fluidNamespace)
    {
        $namespace = $fluidNamespace;
        $suffixLength = strlen(Patterns::NAMESPACESUFFIX);
        $phpNamespaceSuffix = str_replace('/', '\\', Patterns::NAMESPACESUFFIX);
        $extractedSuffix = substr($fluidNamespace, 0 - $suffixLength);
        if (strpos($fluidNamespace, Patterns::NAMESPACEPREFIX) === 0 && $extractedSuffix === Patterns::NAMESPACESUFFIX) {
            // convention assumed: URL starts with prefix and ends with suffix
            $namespace = substr($fluidNamespace, strlen(Patterns::NAMESPACEPREFIX));
        }
        $namespace = str_replace('/', '\\', $namespace);
        if (substr($namespace, 0 - strlen($phpNamespaceSuffix)) !== $phpNamespaceSuffix) {
            $namespace .= $phpNamespaceSuffix;
        }
        return $namespace;
    }

    /**
     * Set all namespaces as an array of ['identifier' => ['Php\Namespace1', 'Php\Namespace2']]
     * namespace definitions. For convenience and legacy support, a
     * format of ['identifier' => 'Only\Php\Namespace'] is allowed,
     * but will internally convert the namespace to an array and
     * allow it to be extended by addNamespace().
     *
     * Note that when using this method the default "f" namespace is
     * also removed and so must be included in $namespaces or added
     * after using addNamespace(). Or, add the PHP namespaces that
     * belonged to "f" as a new alias and use that in your templates.
     *
     * Use getNamespaces() to get an array of currently added namespaces.
     *
     * @param array $namespaces
     */
    public function setNamespaces(array $namespaces)
    {
        $this->namespaces = [];
        foreach ($namespaces as $identifier => $phpNamespace) {
            $this->namespaces[$identifier] = $phpNamespace === null ? null : (array)$phpNamespace;
        }
    }

    /**
     * Validates the given namespaceIdentifier and returns FALSE
     * if the namespace is unknown, causing the tag to be rendered
     * without processing.
     *
     * @param string $namespaceIdentifier
     * @return bool TRUE if the given namespace is valid, otherwise FALSE
     */
    public function isNamespaceValid($namespaceIdentifier)
    {
        if (!array_key_exists($namespaceIdentifier, $this->namespaces)) {
            return false;
        }

        return $this->namespaces[$namespaceIdentifier] !== null;
    }

    /**
     * Validates the given namespaceIdentifier and returns FALSE
     * if the namespace is unknown and not ignored
     *
     * @param string $namespaceIdentifier
     * @return bool TRUE if the given namespace is valid, otherwise FALSE
     */
    public function isNamespaceValidOrIgnored($namespaceIdentifier)
    {
        if ($this->isNamespaceValid($namespaceIdentifier) === true) {
            return true;
        }

        if (array_key_exists($namespaceIdentifier, $this->namespaces)) {
            return true;
        }

        if ($this->isNamespaceIgnored($namespaceIdentifier)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $namespaceIdentifier
     * @return bool
     */
    public function isNamespaceIgnored($namespaceIdentifier)
    {
        if (array_key_exists($namespaceIdentifier, $this->namespaces)) {
            return $this->namespaces[$namespaceIdentifier] === null;
        }
        foreach (array_keys($this->namespaces) as $existingNamespaceIdentifier) {
            if (strpos($existingNamespaceIdentifier, '*') === false) {
                continue;
            }
            $pattern = '/' . str_replace(['.', '*'], ['\\.', '[a-zA-Z0-9\.]*'], $existingNamespaceIdentifier) . '/';
            if (preg_match($pattern, $namespaceIdentifier) === 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * Resolves a ViewHelper class name by namespace alias and
     * Fluid-format identity, e.g. "f" and "format.htmlspecialchars".
     *
     * Looks in all PHP namespaces which have been added for the
     * provided alias, starting in the last added PHP namespace. If
     * a ViewHelper class exists in multiple PHP namespaces Fluid
     * will detect and use whichever one was added last.
     *
     * If no ViewHelper class can be detected in any of the added
     * PHP namespaces a Fluid Parser Exception is thrown.
     *
     * @param string $namespaceIdentifier
     * @param string $methodIdentifier
     * @return string|null
     * @throws ParserException
     */
    public function resolveViewHelperClassName($namespaceIdentifier, $methodIdentifier)
    {
        if (!isset($this->resolvedViewHelperClassNames[$namespaceIdentifier][$methodIdentifier])) {
            $resolvedViewHelperClassName = $this->resolveViewHelperName($namespaceIdentifier, $methodIdentifier);
            $actualViewHelperClassName = implode('\\', array_map('ucfirst', explode('.', $resolvedViewHelperClassName)));
            if (false === class_exists($actualViewHelperClassName) || $actualViewHelperClassName === false) {
                throw new ParserException(sprintf(
                    'The ViewHelper "<%s:%s>" could not be resolved.' . chr(10) .
                    'Based on your spelling, the system would load the class "%s", however this class does not exist.',
                    $namespaceIdentifier,
                    $methodIdentifier,
                    $resolvedViewHelperClassName
                ), 1407060572);
            }
            $this->resolvedViewHelperClassNames[$namespaceIdentifier][$methodIdentifier] = $actualViewHelperClassName;
        }
        return $this->resolvedViewHelperClassNames[$namespaceIdentifier][$methodIdentifier];
    }

    /**
     * Can be overridden by custom implementations to change the way
     * classes are loaded when the class is a ViewHelper - for
     * example making it possible to use a DI-aware class loader.
     *
     * @param string $namespace
     * @param string $viewHelperShortName
     * @return ViewHelperInterface
     */
    public function createViewHelperInstance($namespace, $viewHelperShortName)
    {
        $className = $this->resolveViewHelperClassName($namespace, $viewHelperShortName);
        return $this->createViewHelperInstanceFromClassName($className);
    }

    /**
     * Wrapper to create a ViewHelper instance by class name. This is
     * the final method called when creating ViewHelper classes -
     * overriding this method allows custom constructors, dependency
     * injections etc. to be performed on the ViewHelper instance.
     *
     * @param string $viewHelperClassName
     * @return ViewHelperInterface
     */
    public function createViewHelperInstanceFromClassName($viewHelperClassName)
    {
        return new $viewHelperClassName();
    }

    /**
     * Return an array of ArgumentDefinition instances which describe
     * the arguments that the ViewHelper supports. By default, the
     * arguments are simply fetched from the ViewHelper - but custom
     * implementations can if necessary add/remove/replace arguments
     * which will be passed to the ViewHelper.
     *
     * @param ViewHelperInterface $viewHelper
     * @return ArgumentDefinition[]
     */
    public function getArgumentDefinitionsForViewHelper(ViewHelperInterface $viewHelper)
    {
        return $viewHelper->prepareArguments();
    }

    /**
     * Resolve a viewhelper name.
     *
     * @param string $namespaceIdentifier Namespace identifier for the view helper.
     * @param string $methodIdentifier Method identifier, might be hierarchical like "link.url"
     * @return string The fully qualified class name of the viewhelper
     */
    protected function resolveViewHelperName($namespaceIdentifier, $methodIdentifier)
    {
        $explodedViewHelperName = explode('.', $methodIdentifier);
        if (count($explodedViewHelperName) > 1) {
            $className = implode('\\', array_map('ucfirst', $explodedViewHelperName));
        } else {
            $className = ucfirst($explodedViewHelperName[0]);
        }
        $className .= 'ViewHelper';

        $namespaces = (array)$this->namespaces[$namespaceIdentifier];

        do {
            $name = rtrim((string)array_pop($namespaces), '\\') . '\\' . $className;
        } while (!class_exists($name) && count($namespaces));

        return $name;
    }
}
