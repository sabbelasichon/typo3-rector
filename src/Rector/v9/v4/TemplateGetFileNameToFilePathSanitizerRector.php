<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85445-TemplateService-getFileName.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\TemplateGetFileNameToFilePathSanitizerRector\TemplateGetFileNameToFilePathSanitizerRectorTest
 */
final class TemplateGetFileNameToFilePathSanitizerRector extends AbstractRector
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'tmpl'
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'getFileName')) {
            return null;
        }

        if (! isset($node->args[0])) {
            return null;
        }

        $filePath = new String_($node->args[0]->value);

        return $this->createSanitizeMethod($filePath);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use FilePathSanitizer->sanitize() instead of TemplateService->getFileName()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$fileName = $GLOBALS['TSFE']->tmpl->getFileName('foo.text');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$fileName = GeneralUtility::makeInstance(FilePathSanitizer::class)->sanitize((string) 'foo.text');
CODE_SAMPLE
            ),
        ]);
    }

    private function createSanitizeMethod(String_ $filePath): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'makeInstance',
                [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Frontend\Resource\FilePathSanitizer')]
            ),
            'sanitize',
            [$filePath]
        );
    }
}
