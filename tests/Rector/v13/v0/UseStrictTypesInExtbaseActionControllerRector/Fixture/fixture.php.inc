<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\UseStrictTypesInExtbaseActionControllerRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    protected $responseFactory;
    protected $streamFactory;
    protected $reflectionService;
    protected $hashService;
    public $defaultViewObjectName = \TYPO3\CMS\Fluid\View\TemplateView::class;
    protected $actionMethodName = 'indexAction';
    public $errorMethodName = 'myAction';
    protected $mvcPropertyMappingConfigurationService;
    protected $eventDispatcher;
    protected $uriBuilder;
    protected $settings;
    protected $validatorResolver;
    protected $arguments;
    protected $configurationManager;
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\UseStrictTypesInExtbaseActionControllerRector\Fixture;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    protected ResponseFactoryInterface $responseFactory;
    protected StreamFactoryInterface $streamFactory;
    protected ReflectionService $reflectionService;
    protected HashService $hashService;
    public string $defaultViewObjectName = TemplateView::class;
    protected string $actionMethodName = 'indexAction';
    public string $errorMethodName = 'myAction';
    protected MvcPropertyMappingConfigurationService $mvcPropertyMappingConfigurationService;
    protected EventDispatcherInterface $eventDispatcher;
    protected UriBuilder $uriBuilder;
    protected array $settings;
    protected ValidatorResolver $validatorResolver;
    protected Arguments $arguments;
    protected ConfigurationManagerInterface $configurationManager;
}
?>
