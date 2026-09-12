<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109551-GeneralUtilityGetIndpEnv.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\MigrateGeneralUtilityGetIndpEnvRectorTest
 */
final class MigrateGeneralUtilityGetIndpEnvRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, string>
     */
    private const ENV_NAME_TO_METHOD_NAME = [
        'HTTP_HOST' => 'getHttpHost',
        'TYPO3_HOST_ONLY' => 'getRequestHostOnly',
        'TYPO3_PORT' => 'getRequestPort',
        'PATH_INFO' => 'getPathInfo',
        'QUERY_STRING' => 'getQueryString',
        'REQUEST_URI' => 'getRequestUri',
        'HTTP_REFERER' => 'getHttpReferer',
        'TYPO3_REQUEST_HOST' => 'getRequestHost',
        'TYPO3_REQUEST_URL' => 'getRequestUrl',
        'TYPO3_REQUEST_SCRIPT' => 'getRequestScript',
        'TYPO3_REQUEST_DIR' => 'getRequestDir',
        'TYPO3_SITE_URL' => 'getSiteUrl',
        'TYPO3_SITE_PATH' => 'getSitePath',
        'TYPO3_SITE_SCRIPT' => 'getSiteScript',
        'TYPO3_SSL' => 'isHttps',
        'TYPO3_REV_PROXY' => 'isBehindReverseProxy',
        'SCRIPT_NAME' => 'getScriptName',
        'TYPO3_DOCUMENT_ROOT' => 'getDocumentRoot',
        'SCRIPT_FILENAME' => 'getScriptFilename',
        'REMOTE_ADDR' => 'getRemoteAddress',
        'REMOTE_HOST' => 'getRemoteHost',
        'HTTP_USER_AGENT' => 'getHttpUserAgent',
        'HTTP_ACCEPT_LANGUAGE' => 'getHttpAcceptLanguage',
        'HTTP_ACCEPT_ENCODING' => 'getHttpAcceptEncoding',
    ];

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace calls to `GeneralUtility::getIndpEnv()` with the corresponding `NormalizedParams` getter',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
$host = GeneralUtility::getIndpEnv('HTTP_HOST');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$siteUrl = $GLOBALS['TYPO3_REQUEST']->getAttribute('normalizedParams')->getSiteUrl();
$host = $GLOBALS['TYPO3_REQUEST']->getAttribute('normalizedParams')->getHttpHost();
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        $host = GeneralUtility::getIndpEnv('HTTP_HOST');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $siteUrl = $this->request->getAttribute('normalizedParams')->getSiteUrl();
        $host = $this->request->getAttribute('normalizedParams')->getHttpHost();
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'getIndpEnv')) {
            return null;
        }

        if (! $this->isObjectType($node->class, new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility'))) {
            return null;
        }

        $firstArg = $node->getArgs()[0] ?? null;
        if ($firstArg === null) {
            return null;
        }

        if (! $firstArg->value instanceof String_) {
            return null;
        }

        $methodName = self::ENV_NAME_TO_METHOD_NAME[$firstArg->value->value] ?? null;
        if ($methodName === null) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $requestVar = $this->getTYPO3RequestInScope($scope);

        $getAttributeMethodCall = $this->nodeFactory->createMethodCall(
            $requestVar,
            'getAttribute',
            ['normalizedParams']
        );

        return $this->nodeFactory->createMethodCall($getAttributeMethodCall, $methodName);
    }

    /**
     * @return ArrayDimFetch|PropertyFetch|Variable
     */
    private function getTYPO3RequestInScope(Scope $scope)
    {
        if ($scope->hasVariableType('request')->yes() && $scope->getVariableType('request')->isObject()->yes()) {
            return new Variable('request');
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $classReflection->is('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            return $this->nodeFactory->createPropertyFetch('this', 'request');
        }

        return $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
    }
}
