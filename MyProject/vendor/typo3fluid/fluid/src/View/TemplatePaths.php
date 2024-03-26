<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\View;

use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;

/**
 * Template Paths Holder
 *
 * Class used to hold and resolve template files
 * and paths in multiple supported ways.
 *
 * The purpose of this class is to homogenise the
 * API that is used when working with template
 * paths coming from TypoScript, as well as serve
 * as a way to quickly generate default template-,
 * layout- and partial root paths by package.
 *
 * The constructor accepts two different types of
 * input - anything not of those types is silently
 * ignored:
 *
 * - a `string` input is assumed a package name
 *   and will call the `fillDefaultsByPackageName`
 *   value filling method.
 * - an `array` input is assumed a TypoScript-style
 *   array of root paths in one or more of the
 *   supported structures and will call the
 *   `fillFromTypoScriptArray` method.
 *
 * Either method can also be called after instance
 * is created, but both will overwrite any paths
 * you have previously configured.
 */
class TemplatePaths
{
    public const DEFAULT_FORMAT = 'html';
    public const DEFAULT_TEMPLATES_DIRECTORY = 'Resources/Private/Templates/';
    public const DEFAULT_LAYOUTS_DIRECTORY = 'Resources/Private/Layouts/';
    public const DEFAULT_PARTIALS_DIRECTORY = 'Resources/Private/Partials/';
    public const CONFIG_TEMPLATEROOTPATHS = 'templateRootPaths';
    public const CONFIG_LAYOUTROOTPATHS = 'layoutRootPaths';
    public const CONFIG_PARTIALROOTPATHS = 'partialRootPaths';
    public const CONFIG_FORMAT = 'format';
    public const NAME_TEMPLATES = 'templates';
    public const NAME_LAYOUTS = 'layouts';
    public const NAME_PARTIALS = 'partials';

    /**
     * Holds already resolved identifiers for template files
     *
     * @var array
     */
    protected $resolvedIdentifiers = [
        self::NAME_TEMPLATES => [],
        self::NAME_LAYOUTS => [],
        self::NAME_PARTIALS => []
    ];

    /**
     * Holds already resolved identifiers for template files
     *
     * @var array
     */
    protected $resolvedFiles = [
        self::NAME_TEMPLATES => [],
        self::NAME_LAYOUTS => [],
        self::NAME_PARTIALS => []
    ];

    /**
     * @var array
     */
    protected $templateRootPaths = [];

    /**
     * @var array
     */
    protected $layoutRootPaths = [];

    /**
     * @var array
     */
    protected $partialRootPaths = [];

    /**
     * @var string
     */
    protected $templatePathAndFilename;

    /**
     * @var string
     */
    protected $layoutPathAndFilename;

    /**
     * @var string|null
     */
    protected $templateSource;

    /**
     * @var string
     */
    protected $format = self::DEFAULT_FORMAT;

    /**
     * @param array|string|null $packageNameOrArray
     */
    public function __construct($packageNameOrArray = null)
    {
        if (is_array($packageNameOrArray)) {
            $this->fillFromConfigurationArray($packageNameOrArray);
        } elseif (!empty($packageNameOrArray)) {
            $this->fillDefaultsByPackageName($packageNameOrArray);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::CONFIG_TEMPLATEROOTPATHS => $this->sanitizePaths($this->getTemplateRootPaths()),
            self::CONFIG_LAYOUTROOTPATHS => $this->sanitizePaths($this->getLayoutRootPaths()),
            self::CONFIG_PARTIALROOTPATHS => $this->sanitizePaths($this->getPartialRootPaths())
        ];
    }

    /**
     * @param string $templatePathAndFilename
     */
    public function setTemplatePathAndFilename($templatePathAndFilename)
    {
        $this->templatePathAndFilename = (string)$this->sanitizePath($templatePathAndFilename);
    }

    /**
     * @param string $layoutPathAndFilename
     */
    public function setLayoutPathAndFilename($layoutPathAndFilename)
    {
        $this->layoutPathAndFilename = (string)$this->sanitizePath($layoutPathAndFilename);
    }

    /**
     * @return array
     */
    public function getTemplateRootPaths()
    {
        return $this->templateRootPaths;
    }

    /**
     * @param array $templateRootPaths
     */
    public function setTemplateRootPaths(array $templateRootPaths)
    {
        $this->templateRootPaths = (array)$this->sanitizePaths($templateRootPaths);
        $this->clearResolvedIdentifiersAndTemplates(self::NAME_TEMPLATES);
    }

    /**
     * @return array
     */
    public function getLayoutRootPaths()
    {
        return $this->layoutRootPaths;
    }

    /**
     * @param array $layoutRootPaths
     */
    public function setLayoutRootPaths(array $layoutRootPaths)
    {
        $this->layoutRootPaths = (array)$this->sanitizePaths($layoutRootPaths);
        $this->clearResolvedIdentifiersAndTemplates(self::NAME_LAYOUTS);
    }

    /**
     * @return array
     */
    public function getPartialRootPaths()
    {
        return $this->partialRootPaths;
    }

    /**
     * @param array $partialRootPaths
     */
    public function setPartialRootPaths(array $partialRootPaths)
    {
        $this->partialRootPaths = (array)$this->sanitizePaths($partialRootPaths);
        $this->clearResolvedIdentifiersAndTemplates(self::NAME_PARTIALS);
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Attempts to resolve an absolute filename
     * of a template (i.e. `templateRootPaths`)
     * using a controller name, action and format.
     *
     * Works _backwards_ through template paths in
     * order to achieve an "overlay"-type behavior
     * where the last paths added are the first to
     * be checked and the first path added acts as
     * fallback if no other paths have the file.
     *
     * If the file does not exist in any path,
     * including fallback path, `NULL` is returned.
     *
     * Path configurations filled from TypoScript
     * is automatically recorded in the right
     * order (see `fillFromTypoScriptArray`), but
     * when manually setting the paths that should
     * be checked, you as user must be aware of
     * this reverse behavior (which you should
     * already be, given that it is the same way
     * TypoScript path configurations work).
     *
     * @param string $controller
     * @param string $action
     * @param string $format
     * @return string|null
     * @api
     */
    public function resolveTemplateFileForControllerAndActionAndFormat($controller, $action, $format = null)
    {
        if ($this->templatePathAndFilename !== null) {
            return $this->templatePathAndFilename;
        }
        $format = $format ?: $this->getFormat();
        $controller = str_replace('\\', '/', $controller);
        $action = ucfirst($action);
        $identifier = ltrim($controller . '/' . $action . '.' . $format, '/');
        if (!array_key_exists($identifier, $this->resolvedFiles['templates'])) {
            $templateRootPaths = $this->getTemplateRootPaths();
            foreach ([$controller . '/' . $action, $action] as $possibleRelativePath) {
                $possibleRelativePath = ltrim($possibleRelativePath, '/');
                try {
                    return $this->resolvedFiles['templates'][$identifier] = $this->resolveFileInPaths($templateRootPaths, $possibleRelativePath, $format);
                } catch (InvalidTemplateResourceException $error) {
                    $this->resolvedFiles['templates'][$identifier] = null;
                }
            }
        }
        return isset($this->resolvedFiles[self::NAME_TEMPLATES][$identifier]) ? $this->resolvedFiles[self::NAME_TEMPLATES][$identifier] : null;
    }

    /**
     * @param string|null $controllerName
     * @param string $format
     * @return array
     */
    public function resolveAvailableTemplateFiles($controllerName, $format = null)
    {
        $paths = $this->getTemplateRootPaths();
        foreach ($paths as $index => $path) {
            $paths[$index] = rtrim($path . $controllerName, '/') . '/';
        }
        return $this->resolveFilesInFolders($paths, $format ?: $this->getFormat());
    }

    /**
     * @param string $format
     * @return array
     */
    public function resolveAvailablePartialFiles($format = null)
    {
        return $this->resolveFilesInFolders($this->getPartialRootPaths(), $format ?: $this->getFormat());
    }

    /**
     * @param string $format
     * @return array
     */
    public function resolveAvailableLayoutFiles($format = null)
    {
        return $this->resolveFilesInFolders($this->getLayoutRootPaths(), $format ?: $this->getFormat());
    }

    /**
     * @param array $folders
     * @param string $format
     * @return array
     */
    protected function resolveFilesInFolders(array $folders, $format)
    {
        $files = [];
        foreach ($folders as $folder) {
            $files = array_merge($files, $this->resolveFilesInFolder($folder, $format));
        }
        return array_values($files);
    }

    /**
     * @param string $folder
     * @param string $format
     * @return array
     */
    protected function resolveFilesInFolder($folder, $format)
    {
        if (!is_dir($folder)) {
            return [];
        }

        $directoryIterator = new \RecursiveDirectoryIterator($folder, \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::SKIP_DOTS);
        $recursiveIterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);
        $filterIterator = new \CallbackFilterIterator($recursiveIterator, function ($current, $key, $iterator) use ($format) {
            return $current->getExtension() === $format;
        });

        return array_keys(iterator_to_array($filterIterator));
    }

    /**
     * Fills path arrays based on a traditional
     * TypoScript array which may contain one or
     * more of the supported structures, in order
     * of priority:
     *
     * - `plugin.tx_yourext.view.templateRootPath` and siblings.
     * - `plugin.tx_yourext.view.templateRootPaths` and siblings.
     * - `plugin.tx_yourext.view.overlays.otherextension.templateRootPath` and siblings.
     *
     * The paths are treated as follows, using the
     * `template`-type paths as an example:
     *
     * - If `templateRootPath` is defined, it gets
     *   used as the _first_ path in the internal
     *   paths array.
     * - If `templateRootPaths` is defined, all
     *   values from it are _appended_ to the
     *   internal paths array.
     * - If `overlays.*` exists in the array it is
     *   iterated, each `templateRootPath` entry
     *   from it _appended_ to the internal array.
     *
     * The result is that after filling, the path
     * arrays will contain one or more entries in
     * the order described above, depending on how
     * many of the possible configurations were
     * present in the input array.
     *
     * Will replace any currently configured paths.
     *
     * @param array $paths
     * @api
     */
    public function fillFromConfigurationArray(array $paths)
    {
        list($templateRootPaths, $layoutRootPaths, $partialRootPaths, $format) = $this->extractPathArrays($paths);
        $this->setTemplateRootPaths($templateRootPaths);
        $this->setLayoutRootPaths($layoutRootPaths);
        $this->setPartialRootPaths($partialRootPaths);
        $this->setFormat($format);
    }

    /**
     * Fills path arrays with default expected paths
     * based on package name (converted to extension
     * key automatically).
     *
     * Will replace any currently configured paths.
     *
     * @param string $packageName
     * @api
     */
    public function fillDefaultsByPackageName($packageName)
    {
        $path = $this->getPackagePath($packageName);
        $this->setTemplateRootPaths([$path . self::DEFAULT_TEMPLATES_DIRECTORY]);
        $this->setLayoutRootPaths([$path . self::DEFAULT_LAYOUTS_DIRECTORY]);
        $this->setPartialRootPaths([$path . self::DEFAULT_PARTIALS_DIRECTORY]);
    }

    /**
     * Sanitize a path, ensuring it is absolute and
     * if a directory, suffixed by a trailing slash.
     *
     * @param string|array $path
     * @return string|array<string>
     */
    protected function sanitizePath($path)
    {
        if (is_array($path)) {
            $paths = array_map([$this, 'sanitizePath'], $path);
            return array_unique($paths);
        }
        if (($wrapper = parse_url($path, PHP_URL_SCHEME)) && in_array($wrapper, stream_get_wrappers())) {
            return $path;
        }
        if (!empty($path)) {
            $path = str_replace(['\\', '//'], '/', (string)$path);
            $path = (string)$this->ensureAbsolutePath($path);
            if (is_dir($path)) {
                $path = $this->ensureSuffixedPath($path);
            }
        }
        return $path;
    }

    /**
     * Sanitize paths passing each through sanitizePath().
     *
     * @param array $paths
     * @return array
     */
    protected function sanitizePaths(array $paths)
    {
        return array_unique(array_map([$this, 'sanitizePath'], $paths));
    }

    /**
     * Guarantees that $reference is turned into a
     * correct, absolute path.
     *
     * @param string $path
     * @return string
     */
    protected function ensureAbsolutePath($path)
    {
        return (!empty($path) && $path[0] !== '/' && $path[1] !== ':') ? $this->sanitizePath(realpath($path)) : $path;
    }

    /**
     * Guarantees that array $reference with paths
     * are turned into correct, absolute paths
     *
     * @param array $reference
     * @return array
     */
    protected function ensureAbsolutePaths(array $reference)
    {
        return array_map([$this, 'ensureAbsolutePath'], $reference);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function ensureSuffixedPath($path)
    {
        return $path !== '' ? rtrim($path, '/') . '/' : '';
    }

    /**
     * Extract an array of three arrays of paths, one
     * for each of the types of Fluid file resources.
     * Accepts one or both of the singular and plural
     * path definitions in the input - returns the
     * combined collections of paths based on both
     * the singular and plural entries with the singular
     * entries being recorded first and plurals second.
     *
     * Adds legacy singular name as last option, if set.
     *
     * @param array $paths
     * @return array
     */
    protected function extractPathArrays(array $paths)
    {
        $format = $this->getFormat();
        // pre-processing: if special parameters exist, extract them:
        if (isset($paths[self::CONFIG_FORMAT])) {
            $format = $paths[self::CONFIG_FORMAT];
        }
        $pathParts = [
            self::CONFIG_TEMPLATEROOTPATHS,
            self::CONFIG_LAYOUTROOTPATHS,
            self::CONFIG_PARTIALROOTPATHS
        ];
        $pathCollections = [];
        foreach ($pathParts as $pathPart) {
            $partPaths = [];
            if (isset($paths[$pathPart]) && is_array($paths[$pathPart])) {
                $partPaths = array_merge($partPaths, $paths[$pathPart]);
            }
            $pathCollections[] = array_unique(array_map([$this, 'ensureSuffixedPath'], $partPaths));
        }
        $pathCollections = array_map([$this, 'ensureAbsolutePaths'], $pathCollections);
        $pathCollections[] = $format;
        return $pathCollections;
    }

    /**
     * @param string $packageName
     * @return string
     */
    protected function getPackagePath($packageName)
    {
        return '';
    }

    /**
     * Returns a unique identifier for the resolved layout file.
     * This identifier is based on the template path and last modification date
     *
     * @param string $layoutName The name of the layout
     * @return string layout identifier
     */
    public function getLayoutIdentifier($layoutName = 'Default')
    {
        $filePathAndFilename = $this->getLayoutPathAndFilename($layoutName);
        $layoutName = str_replace('.', '_', $layoutName);
        $prefix = 'layout_' . $layoutName . '_' . $this->getFormat();
        return $this->createIdentifierForFile($filePathAndFilename, $prefix);
    }

    /**
     * Resolve the path and file name of the layout file, based on
     * $this->layoutPathAndFilename and $this->layoutPathAndFilenamePattern.
     *
     * In case a layout has already been set with setLayoutPathAndFilename(),
     * this method returns that path, otherwise a path and filename will be
     * resolved using the layoutPathAndFilenamePattern.
     *
     * @param string $layoutName Name of the layout to use. If none given, use "Default"
     * @return string Path and filename of layout file
     * @throws InvalidTemplateResourceException
     */
    public function getLayoutSource($layoutName = 'Default')
    {
        $layoutPathAndFilename = $this->getLayoutPathAndFilename($layoutName);
        return file_get_contents($layoutPathAndFilename);
    }

    /**
     * Returns a unique identifier for the resolved template file
     * This identifier is based on the template path and last modification date
     *
     * @param string $controller
     * @param string $action Name of the action. If NULL, will be taken from request.
     * @return string template identifier
     */
    public function getTemplateIdentifier($controller = 'Default', $action = 'Default')
    {
        if ($this->templateSource !== null) {
            return 'source_' . sha1($this->templateSource) . '_' . $controller . '_' . $action . '_' . $this->getFormat();
        }
        $templatePathAndFilename = $this->resolveTemplateFileForControllerAndActionAndFormat($controller, $action);
        $prefix = ltrim($controller . '_action_' . $action, '_');
        return $this->createIdentifierForFile($templatePathAndFilename, $prefix);
    }

    /**
     * @param mixed $source
     */
    public function setTemplateSource($source)
    {
        $this->templateSource = $source;
    }

    /**
     * Resolve the template path and filename for the given action. If $actionName
     * is NULL, looks into the current request.
     *
     * @param string $controller
     * @param string $action Name of the action. If NULL, will be taken from request.
     * @return string Full path to template
     * @throws InvalidTemplateResourceException
     */
    public function getTemplateSource($controller = 'Default', $action = 'Default')
    {
        if (is_string($this->templateSource)) {
            return $this->templateSource;
        }
        if (is_resource($this->templateSource)) {
            rewind($this->templateSource);
            return $this->templateSource = stream_get_contents($this->templateSource);
        }
        $templateReference = $this->resolveTemplateFileForControllerAndActionAndFormat($controller, $action);
        if (!file_exists((string)$templateReference) && $templateReference !== 'php://stdin') {
            $format = $this->getFormat();
            throw new InvalidTemplateResourceException(
                sprintf(
                    'Tried resolving a template file for controller action "%s->%s" in format ".%s", but none of the paths ' .
                    'contained the expected template file (%s). %s',
                    $controller,
                    $action,
                    $format,
                    $templateReference === null ? $controller . '/' . ucfirst($action) . '.' . $format : $templateReference,
                    count($this->getTemplateRootPaths()) ? 'The following paths were checked: ' . implode(', ', $this->getTemplateRootPaths()) : 'No paths configured.'
                ),
                1257246929
            );
        }
        return file_get_contents($templateReference);
    }

    /**
     * Returns a unique identifier for the given file in the format
     * <PackageKey>_<SubPackageKey>_<ControllerName>_<prefix>_<SHA1>
     * The SH1 hash is a checksum that is based on the file path and last modification date
     *
     * @param string|null $pathAndFilename
     * @param string $prefix
     * @return string
     */
    protected function createIdentifierForFile($pathAndFilename, $prefix)
    {
        $pathAndFilename = (string)$pathAndFilename;
        $templateModifiedTimestamp = $pathAndFilename !== 'php://stdin' && file_exists($pathAndFilename) ? filemtime($pathAndFilename) : 0;
        return sprintf('%s_%s', $prefix, sha1($pathAndFilename . '|' . $templateModifiedTimestamp));
    }

    /**
     * Resolve the path and file name of the layout file, based on
     * $this->options['layoutPathAndFilename'] and $this->options['layoutPathAndFilenamePattern'].
     *
     * In case a layout has already been set with setLayoutPathAndFilename(),
     * this method returns that path, otherwise a path and filename will be
     * resolved using the layoutPathAndFilenamePattern.
     *
     * @param string $layoutName Name of the layout to use. If none given, use "Default"
     * @return string Path and filename of layout files
     * @throws Exception\InvalidTemplateResourceException
     */
    public function getLayoutPathAndFilename($layoutName = 'Default')
    {
        if ($this->layoutPathAndFilename !== null) {
            return $this->layoutPathAndFilename;
        }
        $layoutName = ucfirst($layoutName);
        $layoutKey = $layoutName . '.' . $this->getFormat();
        if (!array_key_exists($layoutKey, $this->resolvedFiles[self::NAME_LAYOUTS])) {
            $paths = $this->getLayoutRootPaths();
            $this->resolvedFiles[self::NAME_LAYOUTS][$layoutKey] = $this->resolveFileInPaths($paths, $layoutName);
        }
        return $this->resolvedFiles[self::NAME_LAYOUTS][$layoutKey];
    }

    /**
     * Returns a unique identifier for the resolved partial file.
     * This identifier is based on the template path and last modification date
     *
     * @param string $partialName The name of the partial
     * @return string partial identifier
     */
    public function getPartialIdentifier($partialName)
    {
        $partialKey = $partialName . '.' . $this->getFormat();
        if (!array_key_exists($partialKey, $this->resolvedIdentifiers[self::NAME_PARTIALS])) {
            $partialPathAndFilename = $this->getPartialPathAndFilename($partialName);
            $prefix = 'partial_' . $partialName;
            $this->resolvedIdentifiers[self::NAME_PARTIALS][$partialKey] = $this->createIdentifierForFile($partialPathAndFilename, $prefix);
        }
        return $this->resolvedIdentifiers[self::NAME_PARTIALS][$partialKey];
    }

    /**
     * Figures out which partial to use.
     *
     * @param string $partialName The name of the partial
     * @return string contents of the partial template
     * @throws InvalidTemplateResourceException
     */
    public function getPartialSource($partialName)
    {
        $partialPathAndFilename = $this->getPartialPathAndFilename($partialName);
        return file_get_contents($partialPathAndFilename);
    }

    /**
     * Resolve the partial path and filename based on $this->options['partialPathAndFilenamePattern'].
     *
     * @param string $partialName The name of the partial
     * @return string the full path which should be used. The path definitely exists.
     * @throws InvalidTemplateResourceException
     */
    public function getPartialPathAndFilename($partialName)
    {
        $partialKey = $partialName . '.' . $this->getFormat();
        if (!array_key_exists($partialKey, $this->resolvedFiles[self::NAME_PARTIALS])) {
            $paths = $this->getPartialRootPaths();
            $partialName = ucfirst($partialName);
            $this->resolvedFiles[self::NAME_PARTIALS][$partialKey] = $this->resolveFileInPaths($paths, $partialName);
        }
        return $this->resolvedFiles[self::NAME_PARTIALS][$partialKey];
    }

    /**
     * @param array $paths
     * @param string $relativePathAndFilename
     * @param string $format Optional format to resolve.
     * @return string
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     */
    protected function resolveFileInPaths(array $paths, $relativePathAndFilename, $format = null)
    {
        $format = $format ?: $this->getFormat();
        $tried = [];
        // Note about loop: iteration with while + array_pop causes paths to be checked in opposite
        // order, which is intentional. Paths are considered overlays, e.g. adding a path to the
        // array means you want that path checked first.
        while (null !== ($path = array_pop($paths))) {
            $pathAndFilenameWithoutFormat = $path . $relativePathAndFilename;
            $pathAndFilename = $pathAndFilenameWithoutFormat . '.' . $format;
            if (is_file($pathAndFilename)) {
                return $pathAndFilename;
            }
            $tried[] = $pathAndFilename;
            if (is_file($pathAndFilenameWithoutFormat)) {
                return $pathAndFilenameWithoutFormat;
            }
            $tried[] = $pathAndFilenameWithoutFormat;
        }
        throw new InvalidTemplateResourceException(
            'The Fluid template files "' . implode('", "', $tried) . '" could not be loaded.',
            1225709595
        );
    }

    /**
     * @param string|null $type
     */
    protected function clearResolvedIdentifiersAndTemplates($type = null)
    {
        if ($type !== null) {
            $this->resolvedIdentifiers[$type] = $this->resolvedFiles[$type] = [];
        } else {
            $this->resolvedIdentifiers = $this->resolvedFiles = [
                self::NAME_TEMPLATES => [],
                self::NAME_LAYOUTS => [],
                self::NAME_PARTIALS => []
            ];
        }
    }
}
