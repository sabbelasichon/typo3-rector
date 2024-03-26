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

namespace TYPO3\CMS\Webhooks\Message;

use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Attribute\WebhookMessage;
use TYPO3\CMS\Core\Authentication\Event\MfaVerificationFailedEvent;
use TYPO3\CMS\Core\Messaging\WebhookMessageInterface;

/**
 * @internal not part of TYPO3's Core API
 */
#[WebhookMessage(
    identifier: 'typo3/mfa-error',
    description: 'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:sys_webhook.webhook_type.typo3-mfa-error'
)]
final class MfaVerificationErrorOccurredMessage implements WebhookMessageInterface
{
    public function __construct(
        private readonly bool $isFrontend,
        private readonly UriInterface $url,
        private readonly array $details,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'context' => $this->isFrontend ? 'frontend' : 'backend',
            'url' => (string)$this->url,
            'details' => $this->details,
        ];
    }

    public static function createFromEvent(MfaVerificationFailedEvent $event): self
    {
        $user = $event->getUser();

        return new self(
            $event->isFrontendAttempt(),
            $event->getRequest()->getUri(),
            [
                'user' => [
                    'id' => $user->user[$user->userid_column],
                    'name' => $user->user[$user->username_column],
                ],
                'provider' => $event->getProviderIdentifier(),
                'isLocked' => $event->isProviderLocked(),
            ]
        );
    }
}
