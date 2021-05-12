<?php

declare(strict_types=1);

<<<<<<< HEAD:src/FlexForms/Transformer/FlexFormTransformer.php
namespace Ssch\TYPO3Rector\FlexForms\Transformer;
=======
namespace Ssch\TYPO3Rector\FlexForms\Rector;
>>>>>>> 3cd3053a (refresh flex form rectors, apply cs with strict types):src/FlexForms/Rector/FlexFormRector.php

use DOMDocument;
use Rector\Core\Contract\Rector\RectorInterface;

interface FlexFormRector extends RectorInterface
{
    public function transform(DOMDocument $domDocument): bool;
}
