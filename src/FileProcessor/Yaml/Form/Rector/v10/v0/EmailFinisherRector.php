<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\v10\v0;

use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FileProcessor\Yaml\YamlRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Feature-80420-AllowMultipleRecipientsInEmailFinisher.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Yaml\Form\Rector\v10\v0\EmailFinisherRector\EmailFinisherRectorTest
 */
final class EmailFinisherRector implements YamlRectorInterface
{
    /**
     * @var string
     */
    private const FINISHERS = 'finishers';

    /**
     * @var string
     */
    private const OPTIONS = 'options';

    /**
     * @var string
     */
    private const RECIPIENT_ADDRESS = 'recipientAddress';

    /**
     * @var string
     */
    private const RECIPIENTS = 'recipients';

    /**
     * @var string
     */
    private const VARIANTS = 'variants';

    /**
     * @readonly
     */
    private CurrentFileProvider $currentFileProvider;

    public function __construct(CurrentFileProvider $currentFileProvider)
    {
        $this->currentFileProvider = $currentFileProvider;
    }

    /**
     * @param mixed[] $yaml
     */
    public function refactor(array $yaml): array
    {
        $appliedForFinishers = false;
        $appliedForVariants = false;

        if (array_key_exists(self::FINISHERS, $yaml)) {
            $appliedForFinishers = $this->refactorFinishers($yaml[self::FINISHERS], $yaml);
        }

        if (array_key_exists(self::VARIANTS, $yaml)) {
            foreach ($yaml[self::VARIANTS] as $variantKey => $variant) {
                if (! array_key_exists(self::FINISHERS, $variant)) {
                    continue;
                }

                $appliedForVariants = $this->refactorFinishers(
                    $variant[self::FINISHERS],
                    $yaml[self::VARIANTS][$variantKey]
                );
            }
        }

        $file = $this->currentFileProvider->getFile();
        if (($appliedForFinishers || $appliedForVariants) && $file instanceof File) {
            // TODO: How to get the line number of the file?
            $file->addRectorClassWithLine(new RectorWithLineChange($this, 0));
        }

        return $yaml;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert single recipient values to array for EmailFinisher', [
            new CodeSample(
                <<<'CODE_SAMPLE'
finishers:
  -
    options:
      recipientAddress: bar@domain.com
      recipientName: 'Bar'
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
finishers:
  -
    options:
      recipients:
        bar@domain.com: 'Bar'
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param mixed[] $finishers
     * @param mixed[] $yamlToModify
     */
    private function refactorFinishers(array $finishers, &$yamlToModify): bool
    {
        $applied = false;

        foreach ($finishers as $finisherKey => $finisher) {
            if (! array_key_exists('identifier', $finisher)) {
                continue;
            }

            if (! in_array($finisher['identifier'], ['EmailToSender', 'EmailToReceiver'], true)) {
                continue;
            }

            if (! array_key_exists(self::OPTIONS, $finisher)) {
                continue;
            }

            $recipients = [];
            foreach ((array) $finisher[self::OPTIONS] as $optionKey => $optionValue) {
                if (! in_array(
                    $optionKey,
                    [
                        'replyToAddress',
                        'carbonCopyAddress',
                        'blindCarbonCopyAddress',
                        self::RECIPIENT_ADDRESS,
                        'recipientName',
                    ],
                    true
                )) {
                    continue;
                }

                if ($optionKey === 'replyToAddress') {
                    $yamlToModify[self::FINISHERS][$finisherKey][self::OPTIONS]['replyToRecipients'][] = $optionValue;
                } elseif ($optionKey === 'carbonCopyAddress') {
                    $yamlToModify[self::FINISHERS][$finisherKey][self::OPTIONS]['carbonCopyRecipients'][] = $optionValue;
                } elseif ($optionKey === 'blindCarbonCopyAddress') {
                    $yamlToModify[self::FINISHERS][$finisherKey][self::OPTIONS]['blindCarbonCopyRecipients'][] = $optionValue;
                }

                unset(
                    $yamlToModify[self::FINISHERS][$finisherKey][self::OPTIONS][$optionKey]
                );
            }

            if (isset($finisher[self::OPTIONS][self::RECIPIENT_ADDRESS])) {
                $recipients[$finisher[self::OPTIONS][self::RECIPIENT_ADDRESS]] = $finisher[self::OPTIONS]['recipientName'] ?: '';
            }

            if (isset($finisher[self::OPTIONS][self::RECIPIENTS])) {
                $yamlToModify[self::FINISHERS][$finisherKey][self::OPTIONS][self::RECIPIENTS] = array_merge(
                    $recipients,
                    $yamlToModify[self::FINISHERS][$finisherKey][self::OPTIONS][self::RECIPIENTS]
                );
            } else {
                $yamlToModify[self::FINISHERS][$finisherKey][self::OPTIONS][self::RECIPIENTS] = $recipients;
            }

            $applied = true;
        }

        return $applied;
    }
}
