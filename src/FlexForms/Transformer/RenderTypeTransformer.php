<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FlexForms\Transformer;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RenderTypeTransformer implements FlexFormTransformer
{
    public function transform(DOMDocument $domDocument): void
    {
        $xpath = new DOMXPath($domDocument);
        /** @var DOMNodeList|DOMElement[] $elements */
        $elements = $xpath->query('//TCEforms/config');

        foreach ($elements as $element) {
            $type = $element->getElementsByTagName('type')
                ->item(0);

            if (null === $type) {
                continue;
            }

            if ('select' !== $type->textContent) {
                continue;
            }

            $renderType = $element->getElementsByTagName('renderType')
                ->item(0);

            if (null !== $renderType) {
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

            if (null === $insertBefore->parentNode) {
                continue;
            }

            if (null === $insertBefore->nextSibling) {
                continue;
            }

            $insertBefore->parentNode->insertBefore($renderType, $insertBefore->nextSibling);
            $insertBefore->parentNode->insertBefore($domDocument->createTextNode("\n"), $insertBefore->nextSibling);
        }
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
</items>
CODE_SAMPLE
            ),
        ]);
    }
}
