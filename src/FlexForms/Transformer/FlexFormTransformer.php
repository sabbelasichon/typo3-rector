<?php

namespace Ssch\TYPO3Rector\FlexForms\Transformer;

use DOMDocument;

interface FlexFormTransformer
{
    public function transform(DOMDocument $domDocument): void;
}
