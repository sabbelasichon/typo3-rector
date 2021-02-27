<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Information\Typo3Information;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Deprecation-89866-Global-TYPO3-information-related-constants.html
 */
final class UseClassTypo3InformationRector extends AbstractRector
{
    /**
     * @var array
     */
    private const CONSTANTS_TO_REFACTOR = [
        'TYPO3_URL_GENERAL',
        'TYPO3_URL_LICENSE',
        'TYPO3_URL_EXCEPTION',
        'TYPO3_URL_DONATE',
        'TYPO3_URL_WIKI_OPCODECACHE',
    ];

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

        switch ($node->name) {
            case 'TYPO3_URL_GENERAL':
                return $this->nodeFactory->createClassConstFetch(Typo3Information::class, 'URL_COMMUNITY');
            case 'TYPO3_URL_LICENSE':
                return $this->nodeFactory->createClassConstFetch(Typo3Information::class, 'URL_LICENSE');
            case 'TYPO3_URL_EXCEPTION':
                return $this->nodeFactory->createClassConstFetch(Typo3Information::class, 'URL_EXCEPTION');
            case 'TYPO3_URL_DONATE':
                return $this->nodeFactory->createClassConstFetch(Typo3Information::class, 'URL_DONATE');
            case 'TYPO3_URL_WIKI_OPCODECACHE':
                return $this->nodeFactory->createClassConstFetch(Typo3Information::class, 'URL_OPCACHE');
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
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
