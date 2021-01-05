<?php
declare(strict_types = 1);
namespace TYPO3\CMS\Core\Migrations;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Migrate TCA from old to new syntax. Used in bootstrap and Flex Form Data Structures.
 *
 * @internal Class and API may change any time.
 */
class TcaMigration
{
    /**
     * Accumulate migration messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Run some general TCA validations, then migrate old TCA to new TCA.
     *
     * This class is typically called within bootstrap with empty caches after all TCA
     * files from extensions have been loaded. The migration is then applied and
     * the migrated result is cached.
     * For flex form TCA, this class is called dynamically if opening a record in the backend.
     *
     * See unit tests for details.
     *
     * @param array $tca
     * @return array
     */
    public function migrate(array $tca): array
    {
        $tca = $this->migrateLastPiecesOfDefaultExtras($tca);
        $tca = $this->migrateSpecialConfigurationAndRemoveShowItemStylePointerConfig($tca);
        $tca = $this->migrateSuggestWizardTypeGroup($tca);

        return $tca;
    }

    /**
     * Remove "style pointer", the 5th parameter from "types" "showitem" configuration.
     * Move "specConf", 4th parameter from "types" "showitem" to "types" "columnsOverrides".
     *
     * @param array $tca Incoming TCA
     * @return array Modified TCA
     */
    protected function migrateSpecialConfigurationAndRemoveShowItemStylePointerConfig(array $tca): array
    {
        $newTca = $tca;
        foreach ($tca as $table => $tableDefinition) {
            if (!isset($tableDefinition['types']) || !is_array($tableDefinition['types'])) {
                continue;
            }
            foreach ($tableDefinition['types'] as $typeName => $typeArray) {
                if (!isset($typeArray['showitem']) || !is_string($typeArray['showitem']) || strpos($typeArray['showitem'], ';') === false) {
                    // Continue directly if no semicolon is found
                    continue;
                }
                $itemList = GeneralUtility::trimExplode(',', $typeArray['showitem'], true);
                $newFieldStrings = [];
                foreach ($itemList as $fieldString) {
                    $fieldString = rtrim($fieldString, ';');
                    // Unpack the field definition, migrate and remove as much as possible
                    // Keep empty parameters in trimExplode here (third parameter FALSE), so position is not changed
                    $fieldArray = GeneralUtility::trimExplode(';', $fieldString);
                    $fieldArray = [
                        'fieldName' => $fieldArray[0] ?? '',
                        'fieldLabel' => $fieldArray[1] ?? null,
                        'paletteName' => $fieldArray[2] ?? null,
                        'fieldExtra' => $fieldArray[3] ?? null,
                    ];
                    if (!empty($fieldArray['fieldExtra'])) {
                        // Move fieldExtra "specConf" to columnsOverrides "defaultExtras"
                        if (!isset($newTca[$table]['types'][$typeName]['columnsOverrides'])) {
                            $newTca[$table]['types'][$typeName]['columnsOverrides'] = [];
                        }
                        if (!isset($newTca[$table]['types'][$typeName]['columnsOverrides'][$fieldArray['fieldName']])) {
                            $newTca[$table]['types'][$typeName]['columnsOverrides'][$fieldArray['fieldName']] = [];
                        }
                        // Merge with given defaultExtras from columns.
                        // They will be the first part of the string, so if "specConf" from types changes the same settings,
                        // those will override settings from defaultExtras of columns
                        $newDefaultExtras = [];
                        if (!empty($tca[$table]['columns'][$fieldArray['fieldName']]['defaultExtras'])) {
                            $newDefaultExtras[] = $tca[$table]['columns'][$fieldArray['fieldName']]['defaultExtras'];
                        }
                        $newDefaultExtras[] = $fieldArray['fieldExtra'];
                        $newTca[$table]['types'][$typeName]['columnsOverrides'][$fieldArray['fieldName']]['defaultExtras'] = implode(':', $newDefaultExtras);
                    }
                    unset($fieldArray['fieldExtra']);
                    if (count($fieldArray) === 3 && empty($fieldArray['paletteName'])) {
                        unset($fieldArray['paletteName']);
                    }
                    if (count($fieldArray) === 2 && empty($fieldArray['fieldLabel'])) {
                        unset($fieldArray['fieldLabel']);
                    }
                    $newFieldString = implode(';', $fieldArray);
                    if ($newFieldString !== $fieldString) {
                        $this->messages[] = 'The 4th parameter \'specConf\' of the field \'showitem\' with fieldName = \'' . $fieldArray['fieldName'] . '\' has been migrated, from TCA table "'
                            . $table . '[\'types\'][\'' . $typeName . '\'][\'showitem\']"' . 'to "'
                            . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldArray['fieldName'] . '\'][\'defaultExtras\']".';
                    }
                    if (count($fieldArray) === 1 && empty($fieldArray['fieldName'])) {
                        // The field may vanish if nothing is left
                        unset($fieldArray['fieldName']);
                    }
                    if (!empty($newFieldString)) {
                        $newFieldStrings[] = $newFieldString;
                    }
                }
                $newTca[$table]['types'][$typeName]['showitem'] = implode(',', $newFieldStrings);
            }
        }
        return $newTca;
    }


    /**
     * Migrate defaultExtras "nowrap", "enable-tab", "fixed-font". Then drop all
     * remaining "defaultExtras", there shouldn't exist anymore.
     *
     * @param array $tca
     * @return array
     */
    protected function migrateLastPiecesOfDefaultExtras(array $tca): array
    {
        foreach ($tca as $table => &$tableDefinition) {
            if (isset($tableDefinition['columns']) && is_array($tableDefinition['columns'])) {
                foreach ($tableDefinition['columns'] as $fieldName => &$fieldConfig) {
                    if (isset($fieldConfig['defaultExtras'])) {
                        $defaultExtrasArray = GeneralUtility::trimExplode(':', $fieldConfig['defaultExtras'], true);
                        foreach ($defaultExtrasArray as $defaultExtrasSetting) {
                            if ($defaultExtrasSetting === 'rte_only') {
                                $this->messages[] = 'The defaultExtras setting \'rte_only\' in TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'] has been dropped, the setting'
                                    . ' is no longer supported';
                                continue;
                            }
                            if ($defaultExtrasSetting === 'nowrap') {
                                $fieldConfig['config']['wrap'] = 'off';
                                $this->messages[] = 'The defaultExtras setting \'nowrap\' in TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'] has been migrated to TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'][\'config\'][\'wrap\'] = \'off\'';
                            } elseif ($defaultExtrasSetting === 'enable-tab') {
                                $fieldConfig['config']['enableTabulator'] = true;
                                $this->messages[] = 'The defaultExtras setting \'enable-tab\' in TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'] has been migrated to TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'][\'config\'][\'enableTabulator\'] = true';
                            } elseif ($defaultExtrasSetting === 'fixed-font') {
                                $fieldConfig['config']['fixedFont'] = true;
                                $this->messages[] = 'The defaultExtras setting \'fixed-font\' in TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'] has been migrated to TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'][\'config\'][\'fixedFont\'] = true';
                            } else {
                                $this->messages[] = 'The defaultExtras setting \'' . $defaultExtrasSetting . '\' in TCA table '
                                    . $table . '[\'columns\'][\'' . $fieldName . '\'] is unknown and has been dropped.';
                            }
                        }
                        unset($fieldConfig['defaultExtras']);
                    }
                }
            }
            if (isset($tableDefinition['types']) && is_array($tableDefinition['types'])) {
                foreach ($tableDefinition['types'] as $typeName => &$typeArray) {
                    if (isset($typeArray['columnsOverrides']) && is_array($typeArray['columnsOverrides'])) {
                        foreach ($typeArray['columnsOverrides'] as $fieldName => &$overrideConfig) {
                            if (!isset($overrideConfig['defaultExtras'])) {
                                continue;
                            }
                            $defaultExtrasArray = GeneralUtility::trimExplode(':', $overrideConfig['defaultExtras'], true);
                            foreach ($defaultExtrasArray as $defaultExtrasSetting) {
                                if ($defaultExtrasSetting === 'rte_only') {
                                    $this->messages[] = 'The defaultExtras setting \'rte_only\' in TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\']'
                                        . ' has been dropped, the setting is no longer supported';
                                    continue;
                                }
                                if ($defaultExtrasSetting === 'nowrap') {
                                    $overrideConfig['config']['wrap'] = 'off';
                                    $this->messages[] = 'The defaultExtras setting \'nowrap\' in TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\']'
                                        . ' has been migrated to TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\'][\'config\'][\'wrap\'] = \'off\'';
                                } elseif ($defaultExtrasSetting === 'enable-tab') {
                                    $overrideConfig['config']['enableTabulator'] = true;
                                    $this->messages[] = 'The defaultExtras setting \'enable-tab\' in TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\']'
                                        . ' has been migrated to TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\'][\'config\'][\'enableTabulator\'] = true';
                                } elseif ($defaultExtrasSetting === 'fixed-font') {
                                    $overrideConfig['config']['fixedFont'] = true;
                                    $this->messages[] = 'The defaultExtras setting \'fixed-font\' in TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\']'
                                        . ' has been migrated to TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\'][\'config\'][\'fixedFont\'] = true';
                                } else {
                                    $this->messages[] = 'The defaultExtras setting \'' . $defaultExtrasSetting . '\' in TCA table '
                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'' . $fieldName . '\']'
                                        . ' is unknown and has been dropped.';
                                }
                            }
                            unset($overrideConfig['defaultExtras']);
                        }
                    }
                }
            }
        }

        return $tca;
    }

    /**
     * Migrate the "suggest" wizard in type=group to "hideSuggest" and "suggestOptions"
     *
     * @param array $tca Given TCA
     * @return array Modified TCA
     */
    protected function migrateSuggestWizardTypeGroup(array $tca): array
    {
        foreach ($tca as $table => &$tableDefinition) {
            if (isset($tableDefinition['columns']) && is_array($tableDefinition['columns'])) {
                foreach ($tableDefinition['columns'] as $fieldName => &$fieldConfig) {
                    if (isset($fieldConfig['config']['type']) && ($fieldConfig['config']['type'] === 'group'
                        && isset($fieldConfig['config']['internal_type'])
                        && $fieldConfig['config']['internal_type'] === 'db')
                    ) {
                        if (isset($fieldConfig['config']['hideSuggest'])) {
                            continue;
                        }
                        if (isset($fieldConfig['config']['wizards']) && is_array($fieldConfig['config']['wizards'])) {
                            foreach ($fieldConfig['config']['wizards'] as $wizardName => $wizardConfig) {
                                if (isset($wizardConfig['type']) && $wizardConfig['type'] === 'suggest') {
                                    unset($wizardConfig['type']);
                                    if (!empty($wizardConfig)) {
                                        $fieldConfig['config']['suggestOptions'] = $wizardConfig;
                                        $this->messages[] = 'The suggest wizard options in TCA '
                                            . $table . '[\'columns\'][\'' . $fieldName . '\'][\'config\'][\'wizards\'][\'' . $wizardName . '\']'
                                            . ' have been migrated to '
                                            . $table . '[\'columns\'][\'' . $fieldName . '\'][\'config\'][\'suggestOptions\'].';
                                    } else {
                                        $this->messages[] = 'The suggest wizard in TCA '
                                            . $table . '[\'columns\'][\'' . $fieldName . '\'][\'config\'][\'wizards\'][\'' . $wizardName . '\']'
                                            . ' is enabled by default and has been removed.';
                                    }
                                    unset($fieldConfig['config']['wizards'][$wizardName]);
                                }
                            }
                        }
                        if (empty($fieldConfig['config']['wizards'])) {
                            unset($fieldConfig['config']['wizards']);
                        }
                        if (isset($tableDefinition['types']) && is_array($tableDefinition['types'])) {
                            foreach ($tableDefinition['types'] as $typeName => &$typeArray) {
                                if (isset($typeArray['columnsOverrides']) && is_array($typeArray['columnsOverrides'])) {
                                    if (isset($typeArray['columnsOverrides'][$fieldName]['config']['wizards'])
                                        && is_array($typeArray['columnsOverrides'][$fieldName]['config']['wizards'])
                                    ) {
                                        foreach ($typeArray['columnsOverrides'][$fieldName]['config']['wizards'] as $wizardName => $wizard) {
                                            if (isset($wizard['type']) && $wizard['type'] === 'suggest'
                                            ) {
                                                unset($wizard['type']);
                                                $fieldConfig['config']['hideSuggest'] = true;
                                                $typeArray['columnsOverrides'][$fieldName]['config']['hideSuggest'] = false;
                                                if (!empty($wizard)) {
                                                    $typeArray['columnsOverrides'][$fieldName]['config']['suggestOptions'] = $wizard;
                                                    $this->messages[] = 'The suggest wizard options in columnsOverrides have been migrated'
                                                        . ' from TCA ' . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'config\']'
                                                        . '[\'wizards\'][\'' . $wizardName . '\'] to \'suggestOptions\' in '
                                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'config\']';
                                                } else {
                                                    $this->messages[] = 'The suggest wizard in columnsOverrides has been migrated'
                                                        . ' from TCA ' . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'config\']'
                                                        . '[\'wizards\'][\'' . $wizardName . '\'] to \'hideSuggest\' => false in '
                                                        . $table . '[\'types\'][\'' . $typeName . '\'][\'columnsOverrides\'][\'config\'][\'hideSuggest\']';
                                                }
                                                unset($typeArray['columnsOverrides'][$fieldName]['config']['wizards'][$wizardName]);
                                            }
                                        }
                                        if (empty($typeArray['columnsOverrides'][$fieldName]['config']['wizards'])) {
                                            unset($typeArray['columnsOverrides'][$fieldName]['config']['wizards']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $tca;
    }

    /**
     * Migrate some detail options of type=group config
     *
     * @param array $tca Given TCA
     * @return array Modified TCA
     */
    protected function migrateOptionsOfTypeGroup(array $tca): array
    {
        foreach ($tca as $table => &$tableDefinition) {
            if (isset($tableDefinition['columns']) && is_array($tableDefinition['columns'])) {
                foreach ($tableDefinition['columns'] as $fieldName => &$fieldConfig) {
                    if (isset($fieldConfig['config']['type']) && $fieldConfig['config']['type'] === 'group') {
                        if (isset($fieldConfig['config']['show_thumbs'])) {
                            if ((bool)$fieldConfig['config']['show_thumbs'] === false && $fieldConfig['config']['internal_type'] === 'db') {
                                $fieldConfig['config']['fieldWizard']['recordsOverview']['disabled'] = true;
                            } elseif ((bool)$fieldConfig['config']['show_thumbs'] === false && $fieldConfig['config']['internal_type'] === 'file') {
                                $fieldConfig['config']['fieldWizard']['fileThumbnails']['disabled'] = true;
                            }
                        }
                    }
                }
            }
        }

        return $tca;
    }
}
