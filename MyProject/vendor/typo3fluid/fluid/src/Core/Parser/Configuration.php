<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser;

/**
 * The parser configuration. Contains all configuration needed to configure
 * the building of a SyntaxTree.
 */
class Configuration
{
    /**
     * @var bool
     */
    protected $viewHelperArgumentEscapingEnabled = true;

    /**
     * Generic interceptors registered with the configuration.
     *
     * @var InterceptorInterface[]
     */
    protected $interceptors = [];

    /**
     * Escaping interceptors registered with the configuration.
     *
     * @var InterceptorInterface[]
     */
    protected $escapingInterceptors = [];

    /**
     * @return bool
     */
    public function isViewHelperArgumentEscapingEnabled()
    {
        return $this->viewHelperArgumentEscapingEnabled;
    }

    /**
     * @param bool $viewHelperArgumentEscapingEnabled
     */
    public function setViewHelperArgumentEscapingEnabled($viewHelperArgumentEscapingEnabled): void
    {
        $this->viewHelperArgumentEscapingEnabled = (bool)$viewHelperArgumentEscapingEnabled;
    }

    /**
     * Adds an interceptor to apply to values coming from object accessors.
     *
     * @param InterceptorInterface $interceptor
     */
    public function addInterceptor(InterceptorInterface $interceptor)
    {
        $this->addInterceptorToArray($interceptor, $this->interceptors);
    }

    /**
     * Adds an escaping interceptor to apply to values coming from object accessors if escaping is enabled
     *
     * @param InterceptorInterface $interceptor
     */
    public function addEscapingInterceptor(InterceptorInterface $interceptor)
    {
        $this->addInterceptorToArray($interceptor, $this->escapingInterceptors);
    }

    /**
     * Adds an interceptor to apply to values coming from object accessors.
     *
     * @param InterceptorInterface $interceptor
     * @param array $interceptorArray
     */
    protected function addInterceptorToArray(InterceptorInterface $interceptor, array &$interceptorArray)
    {
        foreach ($interceptor->getInterceptionPoints() as $interceptionPoint) {
            if (!isset($interceptorArray[$interceptionPoint])) {
                $interceptorArray[$interceptionPoint] = [];
            }
            $interceptors = &$interceptorArray[$interceptionPoint];
            if (!in_array($interceptor, $interceptors, true)) {
                $interceptors[] = $interceptor;
            }
        }
    }

    /**
     * Returns all interceptors for a given Interception Point.
     *
     * @param int $interceptionPoint one of the InterceptorInterface::INTERCEPT_* constants,
     * @return InterceptorInterface[]
     */
    public function getInterceptors($interceptionPoint): array
    {
        return $this->interceptors[$interceptionPoint] ?? [];
    }

    /**
     * Returns all escaping interceptors for a given Interception Point.
     *
     * @param int $interceptionPoint one of the InterceptorInterface::INTERCEPT_* constants,
     * @return InterceptorInterface[]
     */
    public function getEscapingInterceptors($interceptionPoint): array
    {
        return $this->escapingInterceptors[$interceptionPoint] ?? [];
    }
}
