<?php

namespace Ssch\TYPO3Rector\Yaml\Form\Transformer;

interface FormYamlTransformer
{
    public function transform(array $yaml): array;
}
