<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyRefactoredController extends ActionController
{
    public function throwExceptionWithReturnVoidAction(): void
    {
        $response = $this->responseFactory->createResponse(200);
        throw new \Exception($response, 200);
    }

    public function throwPropagateResponseExceptionWithReturnVoidAction(): void
    {
        $response = $this->responseFactory->createResponse(200);
        throw new PropagateResponseException($response, 200);
    }

    public function throwPropagateResponseExceptionWithReturnResponseInterfaceAction(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(200);
        throw new PropagateResponseException($response, 200);
    }

    public function throwImmediateResponseExceptionWithReturnResponseInterfaceAction(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(200);
        throw new ImmediateResponseException($response, 200);
    }

    public function throwImmediateResponseExceptionWithReturnVoidAction(): void
    {
        $response = $this->responseFactory->createResponse(200);
        throw new ImmediateResponseException($response, 200);
    }

    public function forwardAction(): ResponseInterface
    {
        return new ForwardResponse('another');
    }

    public function closureAction($range): ResponseInterface
    {
        $years = array_map(static function ($item): string {
            return 'C' . $item;
        }, $range);

        return $this->htmlResponse($years);
    }

    public function emptyBodyAction(): ResponseInterface
    {
    }

    abstract public function abstractAction($filter = null, string $letter = ''): ResponseInterface;
}
