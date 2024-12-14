<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.3/Deprecation-89866-Global-TYPO3-information-related-constants.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v3\UseClassTypo3InformationRector\UseClassTypo3InformationRectorTest
 */
final class UseClassTypo3InformationRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string[]
     */
    private const CONSTANTS_TO_REFACTOR = [
        'TYPO3_URL_GENERAL',
        'TYPO3_URL_LICENSE',
        'TYPO3_URL_EXCEPTION',
        'TYPO3_URL_DONATE',
        'TYPO3_URL_WIKI_OPCODECACHE',
    ];

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    /**
     * @param ConstFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isNames($node->name, self::CONSTANTS_TO_REFACTOR)) {
            return null;
        }

        $nodeName = $this->getName($node->name);

        if ($nodeName === 'TYPO3_URL_GENERAL') {
            return $this->nodeFactory->createClassConstFetch(
                'TYPO3\CMS\Core\Information\Typo3Information',
                'URL_COMMUNITY'
            );
        }

        if ($nodeName === 'TYPO3_URL_LICENSE') {
            return $this->nodeFactory->createClassConstFetch(
                'TYPO3\CMS\Core\Information\Typo3Information',
                'URL_LICENSE'
            );
        }

        if ($nodeName === 'TYPO3_URL_EXCEPTION') {
            return $this->nodeFactory->createClassConstFetch(
                'TYPO3\CMS\Core\Information\Typo3Information',
                'URL_EXCEPTION'
            );
        }

        if ($nodeName === 'TYPO3_URL_DONATE') {
            return $this->nodeFactory->createClassConstFetch(
                'TYPO3\CMS\Core\Information\Typo3Information',
                'URL_DONATE'
            );
        }

        if ($nodeName === 'TYPO3_URL_WIKI_OPCODECACHE') {
            return $this->nodeFactory->createClassConstFetch(
                'TYPO3\CMS\Core\Information\Typo3Information',
                'URL_OPCACHE'
            );
        }

        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use class Typo3Information',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$urlGeneral = TYPO3_URL_GENERAL;
$urlLicense = TYPO3_URL_LICENSE;
$urlException = TYPO3_URL_EXCEPTION;
$urlDonate = TYPO3_URL_DONATE;
$urlOpcache = TYPO3_URL_WIKI_OPCODECACHE;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Information\Typo3Information;
$urlGeneral = Typo3Information::TYPO3_URL_GENERAL;
$urlLicense = Typo3Information::TYPO3_URL_LICENSE;
$urlException = Typo3Information::TYPO3_URL_EXCEPTION;
$urlDonate = Typo3Information::TYPO3_URL_DONATE;
$urlOpcache = Typo3Information::TYPO3_URL_WIKI_OPCODECACHE;
CODE_SAMPLE
                ),
            ]
        );
    }
}
