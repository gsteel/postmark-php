<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

/**
 * Settings for Bounce webhooks.
 */
class WebhookConfigurationBounceTrigger implements WebhookConfiguration
{
    /**
     * Create a new WebhookConfigurationBounceTrigger.
     *
     * @param bool $enabled        Specifies whether webhooks will be triggered by Bounce events.
     * @param bool $includeContent Specifies whether the full content of the email bounce is included in webhook POST.
     */
    public function __construct(private bool $enabled = false, private bool $includeContent = false)
    {
    }

    /** @return array{Enabled: bool, IncludeContent:bool} */
    public function jsonSerialize(): array
    {
        return [
            'Enabled' => $this->enabled,
            'IncludeContent' => $this->includeContent,
        ];
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getIncludeContent(): bool
    {
        return $this->includeContent;
    }
}
