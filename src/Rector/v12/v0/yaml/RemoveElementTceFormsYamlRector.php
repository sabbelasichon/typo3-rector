<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\yaml;

use Ssch\TYPO3Rector\Contract\FileProcessor\Yaml\YamlRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97126-TCEformsRemovedInFlexForm.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\yaml\RemoveElementTceFormsYamlRector\RemoveElementTceFormsYamlRectorTest
 */
final class RemoveElementTceFormsYamlRector implements YamlRectorInterface
{
    /**
     * @param mixed[] $yaml
     */
    public function refactor(array $yaml): array
    {
        return $this->removeElementTceFormsRecursive($yaml);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCEForms key from all elements in data structure', [new CodeSample(
            <<<'CODE_SAMPLE'
TYPO3:
  CMS:
    Form:
      prototypes:
        standard:
          finishersDefinition:
            EmailToReceiver:
              FormEngine:
                elements:
                  recipients:
                    el:
                      _arrayContainer:
                        el:
                          email:
                            TCEforms:
                              label: tt_content.finishersDefinition.EmailToSender.recipients.email.label
                              config:
                                type: input
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
TYPO3:
  CMS:
    Form:
      prototypes:
        standard:
          finishersDefinition:
            EmailToReceiver:
              FormEngine:
                elements:
                  recipients:
                    el:
                      _arrayContainer:
                        el:
                          email:
                            label: tt_content.finishersDefinition.EmailToSender.recipients.email.label
                            config:
                              type: input
CODE_SAMPLE
        )]);
    }

    /**
     * Remove "TCEforms" key from all elements in data structure to simplify further parsing.
     *
     * Example config:
     * ['config']['ds']['sheets']['sDEF']['ROOT']['el']['anElement']['TCEforms']['label'] becomes
     * ['config']['ds']['sheets']['sDEF']['ROOT']['el']['anElement']['label']
     *
     * and
     *
     * ['ROOT']['TCEforms']['sheetTitle'] becomes
     * ['ROOT']['sheetTitle']
     *
     * @param mixed[] $structure
     * @return mixed[]
     * @see https://github.com/TYPO3/typo3/blob/a760989e99aebe714ea1ec8d0f948b84a1d77463/typo3/sysext/core/Classes/Configuration/FlexForm/FlexFormTools.php#L1067-L1100
     */
    public function removeElementTceFormsRecursive(array $structure): array
    {
        $newStructure = [];
        foreach ($structure as $key => $value) {
            if ($key === 'ROOT' && is_array($value) && isset($value['TCEforms'])) {
                $value = array_merge($value, $value['TCEforms']);
                unset($value['TCEforms']);
            }

            if ($key === 'el' && is_array($value)) {
                $newSubStructure = [];
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue) && count($subValue) === 1 && isset($subValue['TCEforms'])) {
                        $newSubStructure[$subKey] = $subValue['TCEforms'];
                    } else {
                        $newSubStructure[$subKey] = $subValue;
                    }
                }

                $value = $newSubStructure;
            }

            if (is_array($value)) {
                $value = $this->removeElementTceFormsRecursive($value);
            }

            $newStructure[$key] = $value;
        }

        return $newStructure;
    }
}
