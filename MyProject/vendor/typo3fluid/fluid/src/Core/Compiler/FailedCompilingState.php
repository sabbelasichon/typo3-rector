<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Compiler;

use TYPO3Fluid\Fluid\Core\Parser\ParsedTemplateInterface;
use TYPO3Fluid\Fluid\Core\Parser\ParsingState;

/**
 * Class FailedCompilingState
 *
 * Replacement ParsingState used when a template fails to compile.
 * Includes additional reasons why compiling failed.
 */
class FailedCompilingState extends ParsingState implements ParsedTemplateInterface
{
    /**
     * @var string
     */
    protected $failureReason;

    /**
     * @var string[]
     */
    protected $mitigations = [];

    /**
     * @return string
     */
    public function getFailureReason()
    {
        return $this->failureReason;
    }

    /**
     * @param string $failureReason
     */
    public function setFailureReason($failureReason)
    {
        $this->failureReason = $failureReason;
    }

    /**
     * @return array
     */
    public function getMitigations()
    {
        return $this->mitigations;
    }

    /**
     * @param array $mitigations
     */
    public function setMitigations(array $mitigations)
    {
        $this->mitigations = $mitigations;
    }

    /**
     * @param string $mitigation
     */
    public function addMitigation($mitigation)
    {
        $this->mitigations[] = $mitigation;
    }
}
