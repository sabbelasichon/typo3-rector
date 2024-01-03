<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\__Major__\__Minor__\__Type__;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Ssch\TYPO3Rector\Helper\FlexFormHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog __Changelog_Url__
 * @see \Ssch\TYPO3Rector\Tests\Rector\__Major__\__Minor__\__Type__\__Test_Directory__\__Name__Test
 */
final class __Name__ implements FlexFormRectorInterface
{
    use FlexFormHelperTrait;

    private bool $domDocumentHasBeenChanged = false;

    public function transform(DOMDocument $domDocument): bool
    {
        $xpath = new DOMXPath($domDocument);

        /** @var DOMNodeList<DOMElement> $elements */
        $elements = $xpath->query('//config');

        if ($elements->count() === 0) {
            return false;
        }

        foreach ($elements as $element) {
            $this->refactorColumn($domDocument, $element);
        }

        return $this->domDocumentHasBeenChanged;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('__Description__', [new CodeSample(
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>

        </el>
    </ROOT>
</T3DataStructure>
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
<T3DataStructure>
    <ROOT>
        <sheetTitle>aTitle</sheetTitle>
        <type>array</type>
        <el>

        </el>
    </ROOT>
</T3DataStructure>
CODE_SAMPLE
        )]);
    }

    private function refactorColumn(DOMDocument $domDocument, ?DOMElement $configElement): void
    {
        if (!$configElement instanceof DOMElement) {
            return;
        }

        // Your code here

        $this->domDocumentHasBeenChanged = true;
    }
}
