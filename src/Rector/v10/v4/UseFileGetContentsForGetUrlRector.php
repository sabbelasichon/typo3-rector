<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ErrorSuppress;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.4/Deprecation-90956-AlternativeFetchMethodsAndReportsForGeneralUtilitygetUrl.html
 */
final class UseFileGetContentsForGetUrlRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'getUrl')) {
            return null;
        }

        // Only calls with the url argument are rewritten
        if (count($node->args) > 1) {
            return null;
        }

        $urlValue = $this->getValue($node->args[0]->value);

        if (null === $urlValue) {
            return null;
        }

        // Cannot rewrite for external urls
        if (preg_match('#^(?:http|ftp)s?|s(?:ftp|cp):#', $urlValue)) {
            return $this->createMethodCall(
                $this->createMethodCall(
                    $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
                        $this->createClassConstantReference(RequestFactory::class),
                    ]), 'request', $node->args),
                    'getBody'
                ),
                'getContents'
            );
        }

        return new ErrorSuppress($this->createFuncCall('file_get_contents', $node->args));
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Rewirte Method Calls of GeneralUtility::getUrl("somefile.csv") to @file_get_contents',
            [
                new CodeSample(
                    <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getUrl('some.csv');
$externalUrl = 'https://domain.com';
GeneralUtility::getUrl($externalUrl);

PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Core\Http\RequestFactory;

@file_get_contents('some.csv');
$externalUrl = 'https://domain.com';
GeneralUtility::makeInstance(RequestFactory::class)->request($externalUrl)->getBody()->getContents();

PHP
                ),
            ]
        );
    }
}
