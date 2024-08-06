<?php

declare(strict_types=1);

namespace Postmark;

use Postmark\ClientBehaviour\Bounces;
use Postmark\ClientBehaviour\InboundMessages;
use Postmark\ClientBehaviour\MessageStreams;
use Postmark\ClientBehaviour\OutboundMessages;
use Postmark\ClientBehaviour\PostmarkClientBase;
use Postmark\ClientBehaviour\Statistics;
use Postmark\ClientBehaviour\Suppressions;
use Postmark\ClientBehaviour\Templates;
use Postmark\ClientBehaviour\Webhooks;
use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Header;

use function is_int;
use function strtolower;

/** @psalm-import-type HeaderList from PostmarkClientInterface */
final class PostmarkClient extends PostmarkClientBase implements PostmarkClientInterface
{
    use Bounces;
    use InboundMessages;
    use MessageStreams;
    use OutboundMessages;
    use Statistics;
    use Suppressions;
    use Templates;
    use Webhooks;

    private const AUTH_HEADER_NAME = 'X-Postmark-Server-Token';

    protected function authorizationHeaderName(): string
    {
        return self::AUTH_HEADER_NAME;
    }

    public function sendEmail(
        string $from,
        string $to,
        string $subject,
        string|null $htmlBody = null,
        string|null $textBody = null,
        string|null $tag = null,
        bool|null $trackOpens = null,
        string|null $replyTo = null,
        string|null $cc = null,
        string|null $bcc = null,
        array|null $headers = null,
        array|null $attachments = null,
        string|null $trackLinks = null,
        array|null $metadata = null,
        string|null $messageStream = null,
    ): DynamicResponseModel {
        $body = [];
        $body['From'] = $from;
        $body['To'] = $to;
        $body['Cc'] = $cc;
        $body['Bcc'] = $bcc;
        $body['Subject'] = $subject;
        $body['HtmlBody'] = $htmlBody;
        $body['TextBody'] = $textBody;
        $body['Tag'] = $tag;
        $body['ReplyTo'] = $replyTo;
        $body['Headers'] = Header::listFromArray($headers);
        $body['TrackOpens'] = $trackOpens;
        $body['Attachments'] = $attachments;
        $body['Metadata'] = $metadata;
        $body['MessageStream'] = $messageStream;

        // Since this parameter can override a per-server setting
        // we have to check whether it was actually set.
        // And only include it in the API call if that is the case.
        if ($trackLinks !== null) {
            $body['TrackLinks'] = $trackLinks;
        }

        return new DynamicResponseModel($this->processRestRequest('POST', '/email', $body));
    }

    /** @inheritDoc */
    public function sendEmailWithTemplate(
        string $from,
        string $to,
        $templateIdOrAlias,
        $templateModel,
        bool $inlineCss = true,
        string|null $tag = null,
        bool|null $trackOpens = null,
        string|null $replyTo = null,
        string|null $cc = null,
        string|null $bcc = null,
        array|null $headers = null,
        array|null $attachments = null,
        string|null $trackLinks = null,
        array|null $metadata = null,
        string|null $messageStream = null,
    ): DynamicResponseModel {
        $body = [];
        $body['From'] = $from;
        $body['To'] = $to;
        $body['Cc'] = $cc;
        $body['Bcc'] = $bcc;
        $body['Tag'] = $tag;
        $body['ReplyTo'] = $replyTo;
        $body['Headers'] = Header::listFromArray($headers);
        $body['TrackOpens'] = $trackOpens;
        $body['Attachments'] = $attachments;
        $body['TemplateModel'] = $templateModel;
        $body['InlineCss'] = $inlineCss;
        $body['Metadata'] = $metadata;
        $body['MessageStream'] = $messageStream;

        // Since this parameter can override a per-server setting
        // we have to check whether it was actually set.
        // And only include it in the API call if that is the case.
        if ($trackLinks !== null) {
            $body['TrackLinks'] = $trackLinks;
        }

        if (is_int($templateIdOrAlias)) {
            $body['TemplateId'] = $templateIdOrAlias;

            // Uses the Template Alias if specified instead of Template ID.
        } else {
            $body['TemplateAlias'] = $templateIdOrAlias;
        }

        return new DynamicResponseModel($this->processRestRequest('POST', '/email/withTemplate', $body));
    }

    /** @inheritDoc */
    public function sendEmailBatch($emailBatch = []): DynamicResponseModel
    {
        $final = [];

        foreach ($emailBatch as $email) {
            foreach ($email as $key => $emailValue) {
                if (strtolower($key) !== 'headers') {
                    continue;
                }

                /** @psalm-var HeaderList $emailValue */
                $email[$key] = Header::listFromArray($emailValue);
            }

            $final[] = $email;
        }

        return new DynamicResponseModel($this->processRestRequest('POST', '/email/batch', $final));
    }

    /** @inheritDoc */
    public function sendEmailBatchWithTemplate(array $emailBatch = []): DynamicResponseModel
    {
        $final = [];

        foreach ($emailBatch as $email) {
            foreach ($email as $key => $emailValue) {
                if (strtolower($key) !== 'headers') {
                    continue;
                }

                /** @psalm-var HeaderList $emailValue */
                $email[$key] = Header::listFromArray($emailValue);
            }

            $final[] = $email;
        }

        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/email/batchWithTemplates', ['Messages' => $final]),
        );
    }

    public function getServer(): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', '/server'));
    }

    public function editServer(
        string|null $name = null,
        string|null $color = null,
        bool|null $rawEmailEnabled = null,
        bool|null $smtpApiActivated = null,
        string|null $inboundHookUrl = null,
        string|null $bounceHookUrl = null,
        string|null $openHookUrl = null,
        bool|null $postFirstOpenOnly = null,
        bool|null $trackOpens = null,
        string|null $inboundDomain = null,
        int|null $inboundSpamThreshold = null,
        string|null $trackLinks = null,
        string|null $clickHookUrl = null,
        string|null $deliveryHookUrl = null,
    ): DynamicResponseModel {
        $body = [];
        $body['Name'] = $name;
        $body['Color'] = $color;
        $body['RawEmailEnabled'] = $rawEmailEnabled;
        $body['SmtpApiActivated'] = $smtpApiActivated;
        $body['InboundHookUrl'] = $inboundHookUrl;
        $body['BounceHookUrl'] = $bounceHookUrl;
        $body['OpenHookUrl'] = $openHookUrl;
        $body['PostFirstOpenOnly'] = $postFirstOpenOnly;
        $body['TrackOpens'] = $trackOpens;
        $body['InboundDomain'] = $inboundDomain;
        $body['InboundSpamThreshold'] = $inboundSpamThreshold;
        $body['trackLinks'] = $trackLinks;
        $body['ClickHookUrl'] = $clickHookUrl;
        $body['DeliveryHookUrl'] = $deliveryHookUrl;

        return new DynamicResponseModel($this->processRestRequest('PUT', '/server', $body));
    }
}
