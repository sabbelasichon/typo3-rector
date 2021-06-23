<?php
namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;

if(class_exists('TYPO3\CMS\Core\Http\ApplicationType')) {
    return;
}

class ApplicationType
{

    /**
     * @param string $type
     */
    private function __construct($type)
    {
    }

    /**
     * @return $this
     */
    public static function fromRequest(ServerRequestInterface $request)
    {
        return new self('foo');
    }

    /**
     * @return bool
     */
    public function isFrontend()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isBackend()
    {
        return true;
    }
}
