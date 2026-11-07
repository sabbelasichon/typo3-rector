<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use Ssch\TYPO3Rector\NodeFactory\Typo3RequestNodeFactory;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109523-GeneralUtilityIsOnCurrentHostWithoutRequest.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109544-GeneralUtilitySanitizeLocalUrlWithoutRequest.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.3/Deprecation-109548-GeneralUtilityLocationHeaderUrlWithoutRequest.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v3\AddServerRequestArgumentToGeneralUtilityMethodsRector\AddServerRequestArgumentToGeneralUtilityMethodsRectorTest
 */
final class AddServerRequestArgumentToGeneralUtilityMethodsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string[]
     */
    private const METHOD_NAMES = ['isOnCurrentHost', 'sanitizeLocalUrl', 'locationHeaderUrl'];

    /**
     * @readonly
     */
    private Typo3RequestNodeFactory $typo3RequestNodeFactory;

    public function __construct(Typo3RequestNodeFactory $typo3RequestNodeFactory)
    {
        $this->typo3RequestNodeFactory = $typo3RequestNodeFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Pass the PSR-7 request as second argument to `GeneralUtility::isOnCurrentHost()`, `GeneralUtility::sanitizeLocalUrl()` and `GeneralUtility::locationHeaderUrl()`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$isOnCurrentHost = GeneralUtility::isOnCurrentHost($url);
$url = GeneralUtility::sanitizeLocalUrl($url);
$url = GeneralUtility::locationHeaderUrl($path);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$isOnCurrentHost = GeneralUtility::isOnCurrentHost($url, $GLOBALS['TYPO3_REQUEST']);
$url = GeneralUtility::sanitizeLocalUrl($url, $GLOBALS['TYPO3_REQUEST']);
$url = GeneralUtility::locationHeaderUrl($path, $GLOBALS['TYPO3_REQUEST']);
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(string $url): void
    {
        $url = GeneralUtility::sanitizeLocalUrl($url);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(string $url): void
    {
        $url = GeneralUtility::sanitizeLocalUrl($url, $this->request);
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
        if (! $this->isNames($node->name, self::METHOD_NAMES)) {
            return null;
        }

        if (! $this->isObjectType($node->class, new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility'))) {
            return null;
        }

        if ($node->isFirstClassCallable() || count($node->args) !== 1) {
            return null;
        }

        $firstArg = $node->args[0];
        if (! $firstArg instanceof Arg || $firstArg->unpack || $firstArg->name instanceof Identifier) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $node->args[] = new Arg($this->typo3RequestNodeFactory->getServerRequestInScope($scope));

        return $node;
    }
}
