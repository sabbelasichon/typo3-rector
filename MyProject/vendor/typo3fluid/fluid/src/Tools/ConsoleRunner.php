<?php

declare(strict_types=1);

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Tools;

use TYPO3Fluid\Fluid\Core\Cache\FluidCacheWarmupResult;
use TYPO3Fluid\Fluid\Core\Cache\SimpleFileCache;
use TYPO3Fluid\Fluid\Core\Variables\JSONVariableProvider;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Exception;
use TYPO3Fluid\Fluid\View\TemplatePaths;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * @internal
 */
class ConsoleRunner
{
    public const ARGUMENT_HELP = 'help';
    public const ARGUMENT_SOCKET = 'socket';
    public const ARGUMENT_WARMUP = 'warmup';
    public const ARGUMENT_TEMPLATEFILE = 'template';
    public const ARGUMENT_CACHEDIRECTORY = 'cacheDirectory';
    public const ARGUMENT_VARIABLES = 'variables';
    public const ARGUMENT_CONTROLLERNAME = 'controller';
    public const ARGUMENT_CONTROLLERACTION = 'action';
    public const ARGUMENT_BOOTSTRAP = 'bootstrap';
    public const ARGUMENT_TEMPLATEROOTPATHS = 'templateRootPaths';
    public const ARGUMENT_LAYOUTROOTPATHS = 'layoutRootPaths';
    public const ARGUMENT_PARTIALROOTPATHS = 'partialRootPaths';
    public const ARGUMENT_RENDERINGCONTEXT = 'renderingContext';

    /**
     * @var array
     */
    protected $argumentDescriptions = [
        self::ARGUMENT_HELP => 'Shows usage examples',
        self::ARGUMENT_SOCKET => 'Path to socket (ignored unless running as socket server)',
        self::ARGUMENT_WARMUP => 'Run in Warmup Mode (requires templateRootPaths and others, see help text)',
        self::ARGUMENT_TEMPLATEFILE => 'A single template file to render',
        self::ARGUMENT_CACHEDIRECTORY => 'Path to a directory used as cache for compiled Fluid templates',
        self::ARGUMENT_VARIABLES => 'Variables (JSON string or JSON file) to use when rendering',
        self::ARGUMENT_CONTROLLERNAME => 'Controller name to use when rendering in MVC mode',
        self::ARGUMENT_CONTROLLERACTION => 'Controller action when rendering in MVC mode',
        self::ARGUMENT_BOOTSTRAP => 'A PHP file path or name of a PHP class (ClassName::functionToCall) which will bootstrap environment before rendering',
        self::ARGUMENT_TEMPLATEROOTPATHS => 'Template root paths, multiple paths can be passed separated by spaces',
        self::ARGUMENT_PARTIALROOTPATHS => 'Partial root paths, multiple paths can be passed separated by spaces',
        self::ARGUMENT_LAYOUTROOTPATHS => 'Layout root paths, multiple paths can be passed separated by spaces',
        self::ARGUMENT_RENDERINGCONTEXT => 'Class name of custom RenderingContext implementation to use when rendering'
    ];

    /**
     * @param array $arguments
     * @return string|void
     */
    public function handleCommand(array $arguments)
    {
        $arguments = $this->parseAndValidateInputArguments($arguments);
        if (isset($arguments[self::ARGUMENT_HELP])) {
            return $this->dumpHelpHeader() .
                $this->dumpSupportedParameters() .
                $this->dumpusageExample();
        }
        if (isset($arguments[self::ARGUMENT_BOOTSTRAP])) {
            if (is_file($arguments[self::ARGUMENT_BOOTSTRAP])) {
                include $arguments[self::ARGUMENT_BOOTSTRAP];
            } elseif (
                strpos($arguments[self::ARGUMENT_BOOTSTRAP], '::')
                && is_callable(explode('::', $arguments[self::ARGUMENT_BOOTSTRAP]))
            ) {
                call_user_func(explode('::', $arguments[self::ARGUMENT_BOOTSTRAP]));
            } else {
                throw new \InvalidArgumentException(
                    'Provided bootstrap argument is neither a file nor an executable, public, static function!'
                );
            }
        }
        $view = new TemplateView();
        if (isset($arguments[self::ARGUMENT_RENDERINGCONTEXT])) {
            $context = new $arguments[self::ARGUMENT_RENDERINGCONTEXT]($view);
            $view->setRenderingContext($context);
        } else {
            $context = $view->getRenderingContext();
        }
        if (isset($arguments[self::ARGUMENT_CACHEDIRECTORY])) {
            $cache = new SimpleFileCache($arguments[self::ARGUMENT_CACHEDIRECTORY]);
            $context->setCache($cache);
        }
        $paths = $context->getTemplatePaths();
        $paths->fillFromConfigurationArray($arguments);
        if (isset($arguments[self::ARGUMENT_TEMPLATEFILE])) {
            $paths->setTemplatePathAndFilename($arguments[self::ARGUMENT_TEMPLATEFILE]);
        } elseif (isset($arguments[self::ARGUMENT_CONTROLLERNAME])) {
            $context->setControllerName($arguments[self::ARGUMENT_CONTROLLERNAME]);
        } else {
            $paths->setTemplatePathAndFilename('php://stdin');
        }
        if (isset($arguments[self::ARGUMENT_VARIABLES])) {
            $variablesReference = trim($arguments[self::ARGUMENT_VARIABLES]);
            if (!preg_match('/[^a-z0-9\\\:\/\.\s]+/i', $variablesReference)) {
                if (strpos($variablesReference, ':') !== false) {
                    list($variableProviderClassName, $source) = explode(':', $variablesReference, 2);
                } else {
                    $variableProviderClassName = $variablesReference;
                    $source = null;
                }
                /** @var VariableProviderInterface $variableProvider */
                $variableProvider = new $variableProviderClassName();
                $variableProvider->setSource($source);
            } elseif (($variablesReference[0] === '{' && substr($variablesReference, -1) === '}')
                || file_exists($variablesReference)
                || strpos($variablesReference, ':/') !== false
            ) {
                $variableProvider = new JSONVariableProvider();
                $variableProvider->setSource($variablesReference);
            } else {
                $variableProvider = new StandardVariableProvider();
            }
            $context->setVariableProvider($variableProvider);
        }
        if (isset($arguments[self::ARGUMENT_WARMUP])) {
            $result = $context->getCache()->getCacheWarmer()->warm($context);
            return $this->renderWarmupResult($result);
        }
        if (isset($arguments[self::ARGUMENT_SOCKET])) {
            $this->listenIndefinitelyOnSocket($arguments[self::ARGUMENT_SOCKET], $view);
        } else {
            $action = $arguments[self::ARGUMENT_CONTROLLERACTION] ?? null;
            return $view->render($action);
        }
    }

    /**
     * @param FluidCacheWarmupResult $result
     * @return string
     */
    protected function renderWarmupResult(FluidCacheWarmupResult $result)
    {
        $string = PHP_EOL . 'Template cache warmup results' . PHP_EOL . PHP_EOL;
        foreach ($result->getResults() as $templatePathAndFilename => $aspects) {
            $string .= sprintf(
                "%s\n    Compiled? %s\n    Has Layout? %s\n",
                $templatePathAndFilename,
                $aspects[FluidCacheWarmupResult::RESULT_COMPILABLE] ? 'YES' : 'NO',
                $aspects[FluidCacheWarmupResult::RESULT_HASLAYOUT] ? 'YES' : 'NO'
            );
            if (isset($aspects[FluidCacheWarmupResult::RESULT_COMPILABLE])) {
                $string .= sprintf(
                    "    Compiled as: %s\n",
                    $aspects[FluidCacheWarmupResult::RESULT_COMPILEDCLASS]
                );
            }
            if (isset($aspects[FluidCacheWarmupResult::RESULT_FAILURE])) {
                $string .= sprintf(
                    "    Compilation failure reason: %s\n",
                    $aspects[FluidCacheWarmupResult::RESULT_FAILURE]
                );
            }
            if (isset($aspects[FluidCacheWarmupResult::RESULT_MITIGATIONS])) {
                foreach ($aspects[FluidCacheWarmupResult::RESULT_MITIGATIONS] as $index => $mitigation) {
                    $string .= sprintf("    Suggested mitigation #%d: %s\n", $index + 1, $mitigation);
                }
            }
            $string .= PHP_EOL;
        }
        $string .= PHP_EOL;
        return $string;
    }

    /**
     * @param string $socketIdentifier
     * @param ViewInterface $view
     */
    protected function listenIndefinitelyOnSocket($socketIdentifier, ViewInterface $view)
    {
        if (file_exists($socketIdentifier)) {
            unlink($socketIdentifier);
        }
        umask(0);
        if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}:[0-9]{1,5}/', $socketIdentifier)) {
            $socketServer = stream_socket_server('tcp://' . $socketIdentifier);
        } else {
            $socketServer = stream_socket_server('unix://' . $socketIdentifier);
        }
        while ($socket = stream_socket_accept($socketServer, -1)) {
            $input = stream_socket_recvfrom($socket, 1024);
            $templatePathAndFilename = $this->parseTemplatePathAndFilenameFromHeaders($input, $view->getTemplatePaths());
            if (!file_exists($templatePathAndFilename)) {
                $response = $this->createErrorResponse('Not Found', 404);
            } else {
                try {
                    $rendered = $this->renderSocketRequest($templatePathAndFilename, $view);
                    $response = $this->createResponse($rendered);
                } catch (Exception $error) {
                    $response = $this->createErrorResponse($error->getMessage(), 500);
                }
            }
            stream_socket_sendto($socket, $response);
            stream_socket_sendto($socket, "\x0B");
            stream_socket_shutdown($socket, STREAM_SHUT_WR);
        }
    }

    /**
     * @param string $input
     * @param TemplatePaths $paths
     * @return string
     */
    protected function parseTemplatePathAndFilenameFromHeaders($input, TemplatePaths $paths)
    {
        if (strpos($input, "\000") !== false) {
            return $this->parseTemplatePathAndFilenameFromScgiHeaders($input);
        }
        return $this->parseTemplatePathAndFilenameFromProcessedHeaders($input, $paths);
    }

    /**
     * @param string $input
     * @param TemplatePaths $paths
     * @return string
     */
    protected function parseTemplatePathAndFilenameFromProcessedHeaders($input, TemplatePaths $paths)
    {
        $matches = [];
        preg_match('/^GET ([^\s]+)/', $input, $matches);
        $uri = $matches[1];
        if (substr($uri, -1) === '/') {
            $uri .= 'index.html';
        }
        $templateRootPath = reset($paths->getTemplateRootPaths());
        $templateRootPath = rtrim($templateRootPath, '/');
        return $templateRootPath . $uri;
    }

    /**
     * @param string $input
     * @return string
     */
    protected function parseTemplatePathAndFilenameFromScgiHeaders($input)
    {
        $lines = explode("\000", $input);
        $parameters = [];
        while ($name = array_shift($lines)) {
            $parameters[$name] = array_shift($lines);
        }
        return $parameters['DOCUMENT_ROOT'] . $parameters['REQUEST_URI'];
    }

    /**
     * @param string $response
     * @param int $code
     */
    protected function createErrorResponse($response, $code)
    {
        $headers = [
            'HTTP/1.1 ' . $code . ' ' . $response
        ];
        return implode("\n", $headers) . "\n\n" . $response;
    }

    /**
     * @param string $response
     * @return string
     */
    protected function createResponse($response)
    {
        $headers = [
            'HTTP/1.1 200 OK',
            'Cache-Control:no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Connection:keep-alive',
            'Content-Type:text/html;charset=utf-8',
            'Content-Length:' . strlen($response),
            'Pragma:no-cache'
        ];
        return implode("\n", $headers) . "\n\n" . $response;
    }

    /**
     * @param $templatePathAndFilename
     * @param ViewInterface $view
     * @return string
     */
    protected function renderSocketRequest($templatePathAndFilename, ViewInterface $view)
    {
        $view->getTemplatePaths()->setTemplatePathAndFilename($templatePathAndFilename);
        return $view->render();
    }

    /**
     * @param array $arguments
     * @return array
     */
    protected function parseAndValidateInputArguments(array $arguments)
    {
        $allowed = $this->getAllowedParameterNames();
        $argumentPointer = false;
        $parsed = [];
        foreach ($arguments as $argument) {
            if (substr($argument, 0, 2) === '--') {
                $argument = substr($argument, 2);
                if (!in_array($argument, $allowed)) {
                    throw new \InvalidArgumentException('Unsupported argument: ' . $argument);
                }
                $parsed[$argument] = false;
                $argumentPointer = &$parsed[$argument];
            } else {
                if ($argumentPointer === false) {
                    $argumentPointer = $argument;
                } elseif (is_array($argumentPointer)) {
                    $argumentPointer[] = $argument;
                } else {
                    $argumentPointer = [$argumentPointer];
                    $argumentPointer[] = $argument;
                }
            }
        }
        if (isset($parsed[self::ARGUMENT_TEMPLATEROOTPATHS])) {
            $parsed[self::ARGUMENT_TEMPLATEROOTPATHS] = (array)$parsed[self::ARGUMENT_TEMPLATEROOTPATHS];
        }
        if (isset($parsed[self::ARGUMENT_LAYOUTROOTPATHS])) {
            $parsed[self::ARGUMENT_LAYOUTROOTPATHS] = (array)$parsed[self::ARGUMENT_LAYOUTROOTPATHS];
        }
        if (isset($parsed[self::ARGUMENT_PARTIALROOTPATHS])) {
            $parsed[self::ARGUMENT_PARTIALROOTPATHS] = (array)$parsed[self::ARGUMENT_PARTIALROOTPATHS];
        }
        return $parsed;
    }

    /**
     * @return array
     */
    protected function getAllowedParameterNames()
    {
        $reflection = new \ReflectionClass($this);
        return array_values($reflection->getConstants());
    }

    /**
     * @return string
     */
    public function dumpHelpHeader()
    {
        return PHP_EOL .
            '----------------------------------------------------------------------------------------------' . PHP_EOL .
            '				TYPO3 Fluid CLI: Help text' . PHP_EOL .
            '----------------------------------------------------------------------------------------------' .
            PHP_EOL . PHP_EOL;
    }

    /**
     * @return string
     */
    public function dumpSupportedParameters()
    {
        $parameters = $this->getAllowedParameterNames();
        $parameterString = 'Supported parameters:' . PHP_EOL . PHP_EOL;
        foreach ($parameters as $parameter) {
            $parameterString .= "\t" . '--' . str_pad($parameter, 20, ' ') . ' # ' . $this->argumentDescriptions[$parameter] . PHP_EOL;
        }
        return $parameterString . PHP_EOL;
    }

    /**
     * @return string
     */
    public function dumpusageExample()
    {
        return <<< HELP
Use the CLI utility in the following modes:

Interactive mode:

    ./bin/fluid
    (enter fluid template code, then enter key, then ctrl+d to send the input)

Or using STDIN:

    cat mytemplatefile.html | ./bin/fluid

Or using parameters:

    ./bin/fluid --template mytemplatefile.html

To specify multiple values, for example for the templateRootPaths argument:

    ./bin/fluid --templateRootPaths /path/to/first/ /path/to/second/ "/path/with spaces/"

To specify variables, use any JSON source - string of JSON, local file or URI, or class
name of a PHP class implementing DataProviderInterface:

    ./bin/fluid --variables /path/to/fluidvariables.json

    ./bin/fluid --variables unix:/path/to/unixpipe

    ./bin/fluid --variables http://offsite.com/variables.json

    ./bin/fluid --variables `cat /path/to/fluidvariables.json`

    ./bin/fluid --variables "TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider"

    ./bin/fluid --variables "TYPO3Fluid\Fluid\Core\Variables\JSONVariableProvider:/path/to/file.json"

When specifying a VariableProvider class name it is possible to additionally add a
simple string value which gets passed to the VariableProvider through ->setSource()
upon instantiation. If working with custom VariableProviders, check the documentation
for each VariableProvider to know which source types are supported.

Cache warmup can be triggered by calling:

    ./bin/fluid --warmup --cacheDirectory "/path/to/cache"

And should you require it you can pass the class name of a custom RenderingContext (which can return a
custom FluidCacheWarmer instance!):

    ./bin/fluid --warmup --renderingContext "My\\Custom\\RenderingContext"

Furthermore, should you require special bootstrapping of a framework, you can specify
an entry point containing a bootstrap (with or without output, does not matter) which
will be required/included as part of the initialisation.

    ./bin/fluid --warmup --renderingContext "My\\Custom\\RenderingContext" --bootstrap /path/to/bootstrap.php

Or using a public, static function on a class which bootstraps:

    ./bin/fluid --warmup --renderingContext "My\\Custom\\RenderingContext" --bootstrap MyBootstrapClass::bootstrapMethod

When passing a class-and-method bootstrap it is important that the method has no
required arguments and is possible to call as static method.

Note: the bootstrapping can also be used for other cases, but be careful to use
a bootstrapper which does not cause output if you intend to render templates.

A WebSocket mode is available. When starting the CLI utility in WebSocket mode,
very basic HTTP requests are rendered directly by listening on an IP:PORT combination:

    sudo ./bin/fluid --socket 0.0.0.0:8080 --templateRootPaths /path/to/files/

Pointing your browser to http://localhost:8080 should then render the requested
file from the given path, defaulting to `index.html` when URI ends in `/`.

Note that when started this way, there is no DOCUMENT_ROOT except for the root
path you define as templateRootPaths. In this mode, the *FIRST* templateRootPath
gets used as if it were the DOCUMENT_ROOT.

Note also that this mode does not provide any \$_SERVER or other variables of use
as would be done through for example Apache or Nginx.

An additional SocketServer mode is available. When started in SocketServer mode,
the CLI utility can be used as upstream (SCGI currently) in Nginx:

    sudo ./bin/fluid --socket /var/run/fluid.sock

Example SCGI config for Nginx:

    location ~ \.html$ {
        scgi_pass unix:/var/run/fluid.sock;
        include scgi_params;
    }

End of help text for FLuid CLI.
HELP;
    }
}
