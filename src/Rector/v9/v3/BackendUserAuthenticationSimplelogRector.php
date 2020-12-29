<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-84981-BackendUserAuthentication-simplelogDeprecated.html
 */
final class BackendUserAuthenticationSimplelogRector extends AbstractRector
{
    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, BackendUserAuthentication::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'simplelog')) {
            return null;
        }

        /** @var Arg[] $currentArgs */
        $currentArgs = $node->args;

        $message = $this->getValue($currentArgs[0]->value);
        $extKey = $this->getValue($currentArgs[1]->value);
        $details = ($extKey ? '[' . $extKey . '] ' : '') . $message;

        $args = [
            $this->createArg(4),
            $this->createArg(0),
            $currentArgs[2] ?? $this->createArg(0),
            $this->createArg($details),
            $this->createArg([]),
        ];

        return $this->createMethodCall($node->var, 'writelog', $args);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate the method BackendUserAuthentication->simplelog() to BackendUserAuthentication->writelog()',
            [
                new CodeSample(<<<'PHP'
$someObject = GeneralUtility::makeInstance(TYPO3\CMS\Core\Authentication\BackendUserAuthentication::class);
$someObject->simplelog($message, $extKey, $error);
PHP
                    , <<<'PHP'
$someObject = GeneralUtility::makeInstance(TYPO3\CMS\Core\Authentication\BackendUserAuthentication::class);
$someObject->writelog(4, 0, $error, 0, ($extKey ? '[' . $extKey . '] ' : '') . $message, []);
PHP
                ),
            ]
        );
    }
}
