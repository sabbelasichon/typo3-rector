<?php

declare(strict_types=1);

// $container->get(Type::class) → instance of "Type"
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;

// see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
namespace PHPSTORM_META {

    override(\Psr\Container\ContainerInterface::get(0), type(0));

    expectedArguments(
        \PHPStan\PhpDocParser\Ast\Node::getAttribute(),
        0,
        PhpDocAttributeKey::START_AND_END,
        PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
        PhpDocAttributeKey::PARENT,
        PhpDocAttributeKey::ORIG_NODE,
        PhpDocAttributeKey::RESOLVED_CLASS,
    );

    expectedArguments(
        \PHPStan\PhpDocParser\Ast\NodeAttributes::getAttribute(),
        0,
        PhpDocAttributeKey::START_AND_END,
        PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
        PhpDocAttributeKey::PARENT,
        PhpDocAttributeKey::ORIG_NODE,
        PhpDocAttributeKey::RESOLVED_CLASS,
    );

    expectedArguments(
        \PHPStan\PhpDocParser\Ast\Node::hasAttribute(),
        0,
        PhpDocAttributeKey::START_AND_END,
        PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
        PhpDocAttributeKey::PARENT,
        PhpDocAttributeKey::ORIG_NODE,
        PhpDocAttributeKey::RESOLVED_CLASS,
    );


// PhpStorm 2019.1 - add argument autocomplete
// https://blog.jetbrains.com/phpstorm/2019/02/new-phpstorm-meta-php-features/
    expectedArguments(
        \PhpParser\Node::getAttribute(),
        0,
        \Rector\NodeTypeResolver\Node\AttributeKey::SCOPE,
        \Rector\NodeTypeResolver\Node\AttributeKey::REPRINT_RAW_VALUE,
        \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE,
        \Rector\NodeTypeResolver\Node\AttributeKey::IS_UNREACHABLE,
        \Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO,
        \Rector\NodeTypeResolver\Node\AttributeKey::KIND,
        \Rector\NodeTypeResolver\Node\AttributeKey::IS_REGULAR_PATTERN,
        \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME,
        \Rector\NodeTypeResolver\Node\AttributeKey::COMMENTS,
        \Rector\NodeTypeResolver\Node\AttributeKey::VIRTUAL_NODE,
        \Rector\NodeTypeResolver\Node\AttributeKey::RAW_VALUE,
    );

    expectedArguments(
        \PhpParser\Node::setAttribute(),
        0,
        \Rector\NodeTypeResolver\Node\AttributeKey::SCOPE,
        \Rector\NodeTypeResolver\Node\AttributeKey::REPRINT_RAW_VALUE,
        \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE,
        \Rector\NodeTypeResolver\Node\AttributeKey::IS_UNREACHABLE,
        \Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO,
        \Rector\NodeTypeResolver\Node\AttributeKey::KIND,
        \Rector\NodeTypeResolver\Node\AttributeKey::IS_REGULAR_PATTERN,
        \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME,
        \Rector\NodeTypeResolver\Node\AttributeKey::COMMENTS,
        \Rector\NodeTypeResolver\Node\AttributeKey::VIRTUAL_NODE,
        \Rector\NodeTypeResolver\Node\AttributeKey::RAW_VALUE,
    );

// TYPO3

    expectedArguments(
        \TYPO3\CMS\Core\Context\Context::getAspect(),
        0,
        'date',
        'visibility',
        'backend.user',
        'frontend.user',
        'workspace',
        'language',
        'frontend.preview',
    );
    override(\TYPO3\CMS\Core\Context\Context::getAspect(), map([
        'date' => \TYPO3\CMS\Core\Context\DateTimeAspect::class,
        'visibility' => \TYPO3\CMS\Core\Context\VisibilityAspect::class,
        'backend.user' => \TYPO3\CMS\Core\Context\UserAspect::class,
        'frontend.user' => \TYPO3\CMS\Core\Context\UserAspect::class,
        'workspace' => \TYPO3\CMS\Core\Context\WorkspaceAspect::class,
        'language' => \TYPO3\CMS\Core\Context\LanguageAspect::class,
        'frontend.preview' => \TYPO3\CMS\Frontend\Context\PreviewAspect::class,
    ]));
    expectedArguments(
        \TYPO3\CMS\Core\Context\DateTimeAspect::get(),
        0,
        'timestamp',
        'iso',
        'timezone',
        'full',
        'accessTime'
    );
    expectedArguments(
        \TYPO3\CMS\Core\Context\VisibilityAspect::get(),
        0,
        'includeHiddenPages',
        'includeHiddenContent',
        'includeDeletedRecords'
    );
    expectedArguments(
        \TYPO3\CMS\Core\Context\UserAspect::get(),
        0,
        'id',
        'username',
        'isLoggedIn',
        'isAdmin',
        'groupIds',
        'groupNames'
    );
    expectedArguments(
        \TYPO3\CMS\Core\Context\WorkspaceAspect::get(),
        0,
        'id',
        'isLive',
        'isOffline'
    );
    expectedArguments(
        \TYPO3\CMS\Core\Context\LanguageAspect::get(),
        0,
        'id',
        'contentId',
        'fallbackChain',
        'overlayType',
        'legacyLanguageMode',
        'legacyOverlayType'
    );
    expectedArguments(
        \TYPO3\CMS\Frontend\Context\PreviewAspect::get(),
        0,
        'isPreview'
    );

    expectedArguments(
        \Psr\Http\Message\ServerRequestInterface::getAttribute(),
        0,
        'frontend.user',
        'normalizedParams',
        'site',
        'language',
        'routing',
        'module',
        'moduleData',
        'frontend.controller',
        'frontend.typoscript',
        'frontend.cache.collector',
        'frontend.cache.instruction',
        'frontend.page.information',
    );
    override(\Psr\Http\Message\ServerRequestInterface::getAttribute(), map([
        'frontend.user' => \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication::class,
        'normalizedParams' => \TYPO3\CMS\Core\Http\NormalizedParams::class,
        'site' => \TYPO3\CMS\Core\Site\Entity\SiteInterface::class,
        'language' => \TYPO3\CMS\Core\Site\Entity\SiteLanguage::class,
        'routing' => '\TYPO3\CMS\Core\Routing\SiteRouteResult|\TYPO3\CMS\Core\Routing\PageArguments',
        'module' => \TYPO3\CMS\Backend\Module\ModuleInterface::class,
        'moduleData' => \TYPO3\CMS\Backend\Module\ModuleData::class,
        'frontend.controller' => \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::class,
        'frontend.typoscript' => \TYPO3\CMS\Core\TypoScript\FrontendTypoScript::class,
        'frontend.cache.collector' => \TYPO3\CMS\Core\Cache\CacheDataCollector::class,
        'frontend.cache.instruction' => \TYPO3\CMS\Frontend\Cache\CacheInstruction::class,
        'frontend.page.information' => \TYPO3\CMS\Frontend\Page\PageInformation::class,
    ]));

    expectedArguments(
        \TYPO3\CMS\Core\Http\ServerRequest::getAttribute(),
        0,
        'frontend.user',
        'normalizedParams',
        'site',
        'language',
        'routing',
        'module',
        'moduleData'
    );
    override(\TYPO3\CMS\Core\Http\ServerRequest::getAttribute(), map([
        'frontend.user' => \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication::class,
        'normalizedParams' => \TYPO3\CMS\Core\Http\NormalizedParams::class,
        'site' => \TYPO3\CMS\Core\Site\Entity\SiteInterface::class,
        'language' => \TYPO3\CMS\Core\Site\Entity\SiteLanguage::class,
        'routing' => '\TYPO3\CMS\Core\Routing\SiteRouteResult|\TYPO3\CMS\Core\Routing\PageArguments',
        'module' => \TYPO3\CMS\Backend\Module\ModuleInterface::class,
        'moduleData' => \TYPO3\CMS\Backend\Module\ModuleData::class,
    ]));

    override(\TYPO3\CMS\Core\Routing\SiteMatcher::matchRequest(), type(
            \TYPO3\CMS\Core\Routing\SiteRouteResult::class,
            \TYPO3\CMS\Core\Routing\RouteResultInterface::class,
        )
    );

    override(\TYPO3\CMS\Core\Routing\PageRouter::matchRequest(), type(
        \TYPO3\CMS\Core\Routing\PageArguments::class,
        \TYPO3\CMS\Core\Routing\RouteResultInterface::class,
    ));

    override(\Psr\Container\ContainerInterface::get(0), map([
        '' => '@',
    ]));

    override(\Psr\EventDispatcher\EventDispatcherInterface::dispatch(0), map([
        '' => '@',
    ]));

    override(\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(0), map([
        '' => '@'
    ]));
};
