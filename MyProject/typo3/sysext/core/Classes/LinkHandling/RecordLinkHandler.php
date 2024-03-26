<?php

declare(strict_types=1);

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

namespace TYPO3\CMS\Core\LinkHandling;

/**
 * Resolves links to records and the parameters given
 */
class RecordLinkHandler implements LinkHandlingInterface
{
    /**
     * The Base URN for this link handling to act on
     *
     * @var string
     */
    protected $baseUrn = 't3://record';

    /**
     * Returns all valid parameters for linking to a TYPO3 page as a string
     *
     * @throws \InvalidArgumentException
     */
    public function asString(array $parameters): string
    {
        if (empty($parameters['identifier']) || empty($parameters['uid'])) {
            throw new \InvalidArgumentException('The RecordLinkHandler expects identifier and uid as $parameter configuration.', 1486155150);
        }
        $urn = $this->baseUrn;
        $urn .= sprintf('?identifier=%s&uid=%s', $parameters['identifier'], $parameters['uid']);

        if (!empty($parameters['fragment'])) {
            $urn .= sprintf('#%s', $parameters['fragment']);
        }

        return $urn;
    }

    /**
     * Returns all relevant information built in the link to a page (see asString())
     *
     * @throws \InvalidArgumentException
     */
    public function resolveHandlerData(array $data): array
    {
        if (empty($data['identifier']) || empty($data['uid'])) {
            throw new \InvalidArgumentException('The RecordLinkHandler expects identifier, uid as $data configuration', 1486155151);
        }

        return $data;
    }
}
