<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Core\Environment;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Core\Environment;

final class ConstantToEnvironmentCall extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns defined constant to static method call.', [
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

    /**
     * @param Node $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        $constantName = $this->getName($node);
        if (null === $constantName) {
            return null;
        }

        if (!in_array($constantName, [
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
                break;
            case 'PATH_site':
                return $this->createStaticCall(Environment::class, 'getPublicPath');
                break;
            case 'PATH_typo3':
                return $this->createStaticCall(Environment::class, 'getBackendPath');
                break;
            case 'PATH_typo3conf':
                return $this->createStaticCall(Environment::class, 'getLegacyConfigPath');
                break;
            case 'TYPO3_OS':
                return new Node\Expr\BinaryOp\BooleanOr($this->createStaticCall(Environment::class, 'isUnix'), $this->createStaticCall(Environment::class, 'isWindows'));
                break;
            case 'TYPO3_REQUESTTYPE_CLI':
            case 'TYPO3_REQUESTTYPE':
                return $this->createStaticCall(Environment::class, 'isCli');
                break;
        }
    }
}
