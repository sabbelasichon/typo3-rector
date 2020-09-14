<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.4/Deprecation-90800-GeneralUtilityisRunningOnCgiServerApi.html
 */
final class MoveIsRunningOnCgiServerApiToEnvironmentApiRector extends AbstractRector
{
    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'isRunningOnCgiServerApi')) {
            return null;
        }

        return $this->createStaticCall(Environment::class, 'isRunningOnCgiServer', []);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use Environment API to detect if the current PHP is executed via a CGI wrapper script (“SAPI”)', [
            new CodeSample(
                <<<'PHP'
GeneralUtility::isRunningOnCgiServerApi();
PHP
                ,
                <<<'PHP'
Environment::isRunningOnCgiServer();
PHP
            ),
        ]);
    }
}
