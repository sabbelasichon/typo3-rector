<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\FlexForms\Rector\v7\v6;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Ssch\TYPO3Rector\Contract\FileProcessor\FlexForms\Rector\FlexFormRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.6/Deprecation-69822-DeprecateSelectFieldTca.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\FlexForms\Rector\v7\v6\RenderTypeFlexFormRector\RenderTypeFlexFormRectorTest
 */
final class RenderTypeFlexFormRector implements FlexFormRectorInterface
{
    public function transform(DOMDocument $domDocument): bool
    {
        $xpath = new DOMXPath($domDocument);

        /** @var DOMNodeList<DOMElement> $elements */
        $elements = $xpath->query('//TCEforms/config');

        $hasChanged = false;
        foreach ($elements as $element) {
            $type = $element->getElementsByTagName('type')
                ->item(0);

            if (! $type instanceof DOMElement) {
                continue;
            }

            if ($type->textContent !== 'select') {
                continue;
            }

            $renderType = $element->getElementsByTagName('renderType')
                ->item(0);

            // If renderType is already set, migration can be skipped
            if ($renderType instanceof DOMElement) {
                continue;
            }

            $renderMode = $element->getElementsByTagName('renderMode')
                ->item(0);
            $size = $element->getElementsByTagName('size')
                ->item(0);

            $renderTypeName = 'selectSingle';
            $insertBefore = $type;

            if ($renderMode instanceof DOMNode) {
                $renderTypeName = 'selectTree';
                $insertBefore = $renderMode;
            } elseif ($size instanceof DOMNode && (int) $size->textContent > 1) {
                // Could be also selectCheckBox. This is a sensitive default
                $renderTypeName = 'selectMultipleSideBySide';
            }

            $renderType = $domDocument->createElement('renderType', $renderTypeName);

            if (! $insertBefore->parentNode instanceof DOMNode) {
                continue;
            }

            if (! $insertBefore->nextSibling instanceof DOMNode) {
                continue;
            }

            $hasChanged = true;
            $insertBefore->parentNode->insertBefore($renderType, $insertBefore->nextSibling);
            $insertBefore->parentNode->insertBefore($domDocument->createTextNode("\n"), $insertBefore->nextSibling);
        }

        return $hasChanged;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add renderType node in Flexforms xml', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<type>select</type>
<items>
    <numIndex index="0" type="array">
        <numIndex index="0">
            LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:flexforms_general.no-constraint
        </numIndex>
    </numIndex>
</items>
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<type>select</type>
<renderType>selectSingle</renderType>
<items>
    <numIndex index="0" type="array">
        <numIndex index="0">
            LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:flexforms_general.no-constraint
        </numIndex>
    </numIndex>
</items>
CODE_SAMPLE
            ),
        ]);
    }
}
