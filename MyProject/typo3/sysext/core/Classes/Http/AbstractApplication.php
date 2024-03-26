<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Core\ApplicationInterface;

/**
 * @internal
 */
abstract class AbstractApplication implements ApplicationInterface, RequestHandlerInterface
{
    private const MULTI_LINE_HEADERS = [
        'set-cookie',
    ];

    /**
     * @var RequestHandlerInterface|null
     */
    protected $requestHandler;

    /**
     * Outputs content
     */
    protected function sendResponse(ResponseInterface $response)
    {
        if ($response instanceof NullResponse) {
            return;
        }

        // @todo This requires some merge strategy or header callback handling
        if (!headers_sent()) {
            // If the response code was not changed by legacy code (still is 200)
            // then allow the PSR-7 response object to explicitly set it.
            // Otherwise let legacy code take precedence.
            // This code path can be deprecated once we expose the response object to third party code
            if (http_response_code() === 200) {
                header('HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }

            foreach ($response->getHeaders() as $name => $values) {
                if (in_array(strtolower($name), self::MULTI_LINE_HEADERS, true)) {
                    foreach ($values as $value) {
                        header($name . ': ' . $value, false);
                    }
                } else {
                    header($name . ': ' . implode(', ', $values));
                }
            }
        }
        $body = $response->getBody();
        if ($body instanceof SelfEmittableStreamInterface) {
            // Optimization for streams that use php functions like readfile() as fastpath for serving files.
            $body->emit();
        } else {
            echo $body->__toString();
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->requestHandler->handle($request);
        } catch (ImmediateResponseException $exception) {
            $response = $exception->getResponse();
        }
        return $response;
    }

    /**
     * Set up the application and shut it down afterwards
     */
    final public function run()
    {
        $response = $this->handle(ServerRequestFactory::fromGlobals());
        $this->sendResponse($response);
    }
}
