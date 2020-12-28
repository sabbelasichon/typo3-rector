<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-85113-LegacyBackendModuleRoutingMethods.html
 */
final class BackendUtilityGetModuleUrlRector extends AbstractRector
{
    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, BackendUtility::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'getModuleUrl')) {
            return null;
        }
        /** @var Arg[] $args */
        $args = $node->args;
        $firstArgument = array_shift($args);
        if (null === $firstArgument) {
            return null;
        }
        $secondArgument = array_shift($args);
        return $this->createUriBuilderCall($firstArgument, $secondArgument);
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the method BackendUtility::getModuleUrl() to use UriBuilder API', [
            new CodeSample(<<<'PHP'
$moduleName = 'record_edit';
$params = ['pid' => 2];
$url = BackendUtility::getModuleUrl($moduleName, $params);
PHP
, <<<'PHP'
$moduleName = 'record_edit';
$params = ['pid' => 2];
$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute($moduleName, $params);
PHP
),
        ]);
    }

    private function createUriBuilderCall(Arg $firstArgument, ?Arg $secondArgument): MethodCall
    {
        $buildUriArguments = [$firstArgument->value];
        if (null !== $secondArgument) {
            $buildUriArguments[] = $secondArgument->value;
        }
        return $this->createMethodCall(
            $this->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->createClassConstantReference(UriBuilder::class)]
            ),
            'buildUriFromRoute',
            $buildUriArguments
        );
    }
}
