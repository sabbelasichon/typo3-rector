<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Environment;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Core\Environment;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85285-DeprecatedSystemConstants.html
 */
final class ConstantToEnvironmentCallRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns defined constant to static method call of new Environment API.', [
            new CodeSample('PATH_thisScript;', 'Environment::getCurrentScript();'),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    public function refactor(Node $node): ?Node
    {
        $constantName = $this->getName($node);
        if (null === $constantName) {
            return null;
        }

        if (! in_array($constantName, [
            'PATH_thisScript',
            'PATH_site',
            'PATH_typo3',
            'TYPO3_REQUESTTYPE',
            'TYPO3_REQUESTTYPE_CLI',
            'PATH_typo3conf',
            'TYPO3_OS',
        ], false)) {
            return null;
        }

        switch ($constantName) {
            case 'PATH_thisScript':
                return $this->createStaticCall(Environment::class, 'getCurrentScript');
            case 'PATH_site':
                return $this->createStaticCall(Environment::class, 'getPublicPath');
            case 'PATH_typo3':
                return $this->createStaticCall(Environment::class, 'getBackendPath');
            case 'PATH_typo3conf':
                return $this->createStaticCall(Environment::class, 'getLegacyConfigPath');
            case 'TYPO3_OS':
                return new BooleanOr($this->createStaticCall(Environment::class, 'isUnix'), $this->createStaticCall(
                    Environment::class,
                    'isWindows'
                ));
            case 'TYPO3_REQUESTTYPE_CLI':
            case 'TYPO3_REQUESTTYPE':
                return $this->createStaticCall(Environment::class, 'isCli');
        }

        return null;
    }
}
