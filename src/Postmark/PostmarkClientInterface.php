<?php

declare(strict_types=1);

namespace Postmark;

use JsonSerializable;
use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Header;
use Postmark\Models\PostmarkAttachment;
use Postmark\Models\Suppressions\SuppressionChangeRequest;
use Postmark\Models\Webhooks\HttpAuth;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;

/**
 * The contract for the Postmark REST api client
 *
 * phpcs:disable SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming
 *
 * @psalm-type Attachments = list<PostmarkAttachment>|null
 * @psalm-type HeaderList = array<string, scalar|null>|array<array-key, Header>
 * @psalm-type MetaData = array<string, scalar>
 * @psalm-type TemplateId = non-empty-string|positive-int
 * @psalm-type EmailMessage = array{
 *      From: non-empty-string,
 *      To: non-empty-string,
 *      Cc?: non-empty-string|null,
 *      Bcc?: non-empty-string|null,
 *      Subject: non-empty-string,
 *      Tag?: non-empty-string|null,
 *      HtmlBody?: non-empty-string|null,
 *      TextBody?: non-empty-string|null,
 *      ReplyTo?: non-empty-string|null,
 *      Metadata?: MetaData|null,
 *      Headers?: HeaderList,
 *      TrackOpens?: bool|null,
 *      TrackLinks?: string|null,
 *      MessageStream?: string|null
 *  }
 * @psalm-type EmailBatch = list<EmailMessage>
 * @psalm-type TemplateModelObject = array<string, scalar>
 * @psalm-type TemplateModel = array<string, scalar|TemplateModelObject>|JsonSerializable
 * @psalm-type TemplateMessage = array{
 *      TemplateId?: int,
 *      TemplateAlias?: string,
 *      TemplateModel: TemplateModel,
 *      InlineCss?: bool,
 *      From: non-empty-string,
 *      To: non-empty-string,
 *      Cc?: non-empty-string|null,
 *      Bcc?: non-empty-string|null,
 *      Tag?: non-empty-string|null,
 *      ReplyTo?: non-empty-string|null,
 *      Metadata?: MetaData|null,
 *      Headers?: HeaderList,
 *      TrackOpens?: bool|null,
 *      TrackLinks?: string|null,
 *      MessageStream?: string|null
 *  }
 * @psalm-type TemplateBatch = list<TemplateMessage>
 */
interface PostmarkClientInterface
{
    /**
     * Send an email.
     *
     * @param string          $from          The sender of the email. (Your account must have an associated Sender
     *                                       Signature for the address used.)
     * @param string          $to            The recipient of the email.
     * @param string          $subject       The subject of the email.
     * @param string|null     $htmlBody      The HTML content of the message, optional if Text Body is specified.
     * @param string|null     $textBody      The text content of the message, optional if HTML Body is specified.
     * @param string|null     $tag           A tag associated with this message, useful for classifying sent messages.
     * @param bool|null       $trackOpens    True if you want Postmark to track opens of HTML emails.
     * @param string|null     $replyTo       Reply to email address.
     * @param string|null     $cc            Carbon Copy recipients, comma-separated
     * @param string|null     $bcc           Blind Carbon Copy recipients, comma-separated.
     * @param HeaderList|null $headers       Headers to be included with the sent email message.
     * @param Attachments     $attachments   An array of PostmarkAttachment objects.
     * @param string|null     $trackLinks    Can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable link
     *                                       tracking.
     * @param MetaData|null   $metadata      Add metadata to the message. The metadata is an associative array, and
     *                                       values will be evaluated as strings by Postmark.
     * @param string|null     $messageStream The message stream used to send this message. If not provided, the default
     *                                       transactional stream "outbound" will be used.
     */
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
    ): DynamicResponseModel;

    /**
     * Send an email using a template.
     *
     * @param string          $from              The sender of the email. (Your account must have an associated Sender
     *                                           Signature for the address used.)
     * @param string          $to                The recipient of the email.
     * @param TemplateId      $templateIdOrAlias The ID or alias of the template to use to generate the content of this
     *                                           message.
     * @param TemplateModel   $templateModel     The values to combine with the Templated content.
     * @param bool            $inlineCss         If the template contains an HTMLBody, CSS is automatically inlined, you
     *                                           may opt-out of this by passing 'false' for this parameter.
     * @param string|null     $tag               A tag associated with this message, useful for classifying sent
     *                                           messages.
     * @param bool|null       $trackOpens        True if you want Postmark to track opens of HTML emails.
     * @param string|null     $replyTo           Reply to email address.
     * @param string|null     $cc                Carbon Copy recipients, comma-separated
     * @param string|null     $bcc               Blind Carbon Copy recipients, comma-separated.
     * @param HeaderList|null $headers           Headers to be included with the sent email message.
     * @param Attachments     $attachments       An array of PostmarkAttachment objects.
     * @param string|null     $trackLinks        Can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable
     *                                           link tracking.
     * @param MetaData|null   $metadata          Add metadata to the message. The metadata is an associative array , and
     *                                           values will be evaluated as strings by Postmark.
     * @param string|null     $messageStream     The message stream used to send this message. If not provided, the
     *                                           default transactional stream "outbound" will be used.
     * @psalm-param TemplateModel $templateModel
     * @psalm-param HeaderList|null $headers
     */
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
    ): DynamicResponseModel;

    /**
     * Send multiple emails as a batch
     *
     * Each email is an associative array of values, but note that the 'Attachments'
     * key must be an array of 'PostmarkAttachment' objects if you intend to send
     * attachments with an email.
     *
     * @param EmailBatch $emailBatch An array of emails to be sent in one batch.
     */
    public function sendEmailBatch($emailBatch = []): DynamicResponseModel;

    /**
     * Send multiple emails with a template as a batch
     *
     * Each email is an associative array of values. See sendEmailWithTemplate()
     * for details on required values.
     *
     * @param TemplateBatch $emailBatch An array of emails to be sent in one batch.
     * @psalm-param TemplateBatch $emailBatch
     */
    public function sendEmailBatchWithTemplate(array $emailBatch = []): DynamicResponseModel;

    /**
     * Get the settings for the server associated with this PostmarkClient instance
     * (defined by the $server_token you passed when instantiating this client)
     */
    public function getServer(): DynamicResponseModel;

    /**
     * Modify the associated Server. Any parameters passed with NULL will be
     * ignored (their existing values will not be modified).
     *
     * @param string|null $name                 Set the name of the server.
     * @param string|null $color                Set the color for the server in the Postmark WebUI (must be: 'purple',
     *                                          'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param bool|null   $rawEmailEnabled      Enable raw email to be sent with inbound.
     * @param bool|null   $smtpApiActivated     Specifies whether or not SMTP is enabled on this server.
     * @param string|null $inboundHookUrl       URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl        URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl          URL to POST to everytime an open event occurs.
     * @param bool|null   $postFirstOpenOnly    If set to true, only the first open by a particular recipient will
     *                                          initiate the open webhook. Any subsequent opens of the same email by
     *                                          the same recipient will not initiate the webhook.
     * @param bool|null   $trackOpens           Indicates if all emails being sent through this server have open
     *                                          tracking enabled.
     * @param string|null $inboundDomain        Inbound domain for MX setup.
     * @param int|null    $inboundSpamThreshold The maximum spam score for an inbound message before it's
     *                                          blocked (range from 0-30).
     * @param string|null $trackLinks           Indicates if all emails being sent through this server have
     *                                          link tracking enabled.
     * @param string|null $clickHookUrl         URL to POST to everytime an click event occurs.
     * @param string|null $deliveryHookUrl      URL to POST to everytime an click event occurs.
     */
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
    ): DynamicResponseModel;

    /**
     * Get a batch of bounces to be processed.
     *
     * @link http://developer.postmarkapp.com/developer-api-bounce.html#bounce-types)
     *
     * @param int         $count         Number of bounces to retrieve
     * @param int         $offset        How many bounces to skip (when paging through bounces.)
     * @param string|null $type          The bounce type.
     * @param bool|null   $inactive      Specifies if the bounce caused Postmark to deactivate this email.
     * @param string|null $emailFilter   Filter by email address
     * @param string|null $tag           Filter by tag
     * @param string|null $messageID     Filter by MessageID
     * @param string|null $fromdate      Filter for bounces after is date.
     * @param string|null $todate        Filter for bounces before this date.
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getBounces(
        int $count = 100,
        int $offset = 0,
        string|null $type = null,
        bool|null $inactive = null,
        string|null $emailFilter = null,
        string|null $tag = null,
        string|null $messageID = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel;

    /**
     * Locate information on a specific email bounce.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     *
     * @param int|numeric-string $id The ID of the bounce to get.
     */
    public function getBounce($id): DynamicResponseModel; // phpcs:ignore

    /**
     * Get a "dump" for a specific bounce.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     *
     * @param int|numeric-string $id The ID of the bounce for which we want a dump.
     */
    public function getBounceDump($id): DynamicResponseModel; // phpcs:ignore

    /**
     * Cause the email address associated with a Bounce to be reactivated.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     *
     * @param int|numeric-string $id The bounce which has a deactivated email address.
     */
    public function activateBounce($id): DynamicResponseModel; // phpcs:ignore

    /**
     * Get messages sent to the inbound email address associated with this Server.
     *
     * @param int         $count       The number of inbound messages to retrieve in the request (defaults to 100)
     * @param int         $offset      The number of messages to 'skip' when 'paging' through messages (defaults to 0)
     * @param string|null $recipient   Filter by the message recipient
     * @param string|null $fromEmail   Filter by the message sender
     * @param string|null $tag         Filter by the message tag
     * @param string|null $subject     Filter by the message subject
     * @param string|null $mailboxHash Filter by the mailboxHash
     * @param string|null $status      Filter by status ('blocked' or 'processed')
     * @param string|null $fromdate    Filter to messages on or after YYYY-MM-DD
     * @param string|null $todate      Filter to messages on or before YYYY-MM-DD
     */
    public function getInboundMessages(
        int $count = 100,
        int $offset = 0,
        string|null $recipient = null,
        string|null $fromEmail = null,
        string|null $tag = null,
        string|null $subject = null,
        string|null $mailboxHash = null,
        string|null $status = null,
        string|null $fromdate = null,
        string|null $todate = null,
    ): DynamicResponseModel;

    /**
     * Get details for a specific inbound message.
     *
     * @param string $id The ID of the message for which we went to get details.
     */
    public function getInboundMessageDetails(string $id): DynamicResponseModel;

    /**
     * Allow an inbound message to be processed, even though the filtering rules would normally
     * prevent it from being processed.
     *
     * @param string $id The ID for a message that we wish to unblock.
     */
    public function bypassInboundMessageRules(string $id): DynamicResponseModel;

    /**
     * Request that Postmark retry POSTing the specified message to the Server's Inbound Hook.
     *
     * @param string $id The ID for a message that we wish retry the inbound hook for.
     */
    public function retryInboundMessageHook(string $id): DynamicResponseModel;

    /**
     * Create an Inbound Rule to block messages from a single email address, or an entire domain.
     *
     * @param string $rule The email address (or domain) that will be blocked.
     */
    public function createInboundRuleTrigger(string $rule): DynamicResponseModel;

    /**
     * Get a list of all existing Inbound Rule Triggers.
     *
     * @param int $count  The number of rule triggers to return with this request.
     * @param int $offset The number of triggers to 'skip' when 'paging' through rule triggers.
     */
    public function listInboundRuleTriggers(int $count = 100, int $offset = 0): DynamicResponseModel;

    /**
     * Delete an Inbound Rule Trigger.
     *
     * @param int $id The ID of the rule trigger we wish to delete.
     */
    public function deleteInboundRuleTrigger(int $id): DynamicResponseModel;

    /**
     * Create a new message stream on your server
     *
     * Currently, you cannot create multiple inbound streams.
     *
     * @param non-empty-string $id                Identifier for your message stream, unique at server level.
     * @param string           $messageStreamType Type of the message stream. Possible values:
     *                                            ["Transactional", "Inbound", "Broadcasts"].
     * @param string           $name              Friendly name for your message stream.
     * @param string|null      $description       Friendly description for your message stream. (optional)
     */
    public function createMessageStream(
        string $id,
        string $messageStreamType,
        string $name,
        string|null $description = null,
    ): DynamicResponseModel;

    /**
     * Edit the properties of a message stream.
     *
     * @param non-empty-string $id          The identifier for the stream you are trying to update.
     * @param string|null      $name        New friendly name to use. (optional)
     * @param string|null      $description New description to use. (optional)
     */
    public function editMessageStream(
        string $id,
        string|null $name = null,
        string|null $description = null,
    ): DynamicResponseModel;

    /**
     * Retrieve details about a message stream.
     *
     * @param non-empty-string $id Identifier of the stream to retrieve details for.
     */
    public function getMessageStream(string $id): DynamicResponseModel;

    /**
     * Retrieve all message streams on the server.
     *
     * @param non-empty-string $messageStreamType      Filter by stream type. Possible values:
     *                                                 ["Transactional", "Inbound", "Broadcasts", "All"]. Default: All
     * @param bool             $includeArchivedStreams Include archived streams in the result. Defaults to: false.
     */
    public function listMessageStreams(
        string $messageStreamType = 'All',
        bool $includeArchivedStreams = false,
    ): DynamicResponseModel;

    /**
     * Archive a message stream. This will disable sending/receiving messages via that stream.
     * The stream will also stop being shown in the Postmark UI.
     * Once a stream has been archived, it will be deleted (alongside associated data) at the ExpectedPurgeDate
     * in the response.
     *
     * @param non-empty-string $id The identifier for the stream you are trying to update.
     */
    public function archiveMessageStream(string $id): DynamicResponseModel;

    /**
     * Un-archive a message stream. This will resume sending/receiving via that stream.
     * The stream will also re-appear in the Postmark UI.
     * A stream can be unarchived only before the stream ExpectedPurgeDate.
     *
     * @param non-empty-string $id Identifier of the stream to un-archive.
     */
    public function unarchiveMessageStream(string $id): DynamicResponseModel;

    /**
     * Search messages that have been sent using this Server.
     *
     * @param int           $count         How many messages to retrieve at once (defaults to 100)
     * @param int           $offset        How many messages to skip when 'paging' through the massages (defaults to 0)
     * @param string|null   $recipient     Filter by recipient.
     * @param string|null   $fromEmail     Filter by sender email address.
     * @param string|null   $tag           Filter by tag.
     * @param string|null   $subject       Filter by subject.
     * @param string|null   $status        The current status for the outbound messages to return defaults to 'sent'
     * @param string|null   $fromdate      Filter to messages on or after YYYY-MM-DD
     * @param string|null   $todate        Filter to messages on or before YYYY-MM-DD
     * @param MetaData|null $metadata      An associative array of key-values that must all match values included in
     *                                     the metadata of matching sent messages.
     * @param string        $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional
     *                                     stream will be used.
     */
    public function getOutboundMessages(
        int $count = 100,
        int $offset = 0,
        string|null $recipient = null,
        string|null $fromEmail = null,
        string|null $tag = null,
        string|null $subject = null,
        string|null $status = null,
        string|null $fromdate = null,
        string|null $todate = null,
        array|null $metadata = null,
        string $messagestream = 'outbound',
    ): DynamicResponseModel;

    /**
     * Get information related to a specific sent message.
     *
     * @param string $id The ID of the Message for which we want details.
     */
    public function getOutboundMessageDetails(string $id): DynamicResponseModel;

    /**
     * Get the raw content for a message that was sent.
     * This response
     *
     * @param string $id The ID of the message for which we want a dump.
     */
    public function getOutboundMessageDump(string $id): DynamicResponseModel;

    /**
     * Create Suppressions for the specified recipients.
     *
     * Suppressions will be generated with a "Customer" Origin and will have a "ManualSuppression" reason.
     *
     * @param list<SuppressionChangeRequest> $suppressionChanges Array of SuppressionChangeRequest objects that
     *                                                           specify what recipients to suppress.
     * @param string                         $messageStream      Message stream where the recipients should be
     *                                                           suppressed. If not provided, they will be suppressed
     *                                                           on the default transactional stream.
     */
    public function createSuppressions(
        array $suppressionChanges = [],
        string $messageStream = 'outbound',
    ): DynamicResponseModel;

    /**
     * Reactivate Suppressions for the specified recipients.
     *
     * Only 'Customer' origin 'ManualSuppression' suppressions and 'Recipient' origin 'HardBounce'
     * suppressions can be reactivated.
     *
     * @param list<SuppressionChangeRequest> $suppressionChanges Array of SuppressionChangeRequest objects that specify
     *                                                           what recipients to reactivate.
     * @param string                         $messageStream      Message stream where the recipients should be
     *                                                           reactivated. If not provided, they will be reactivated
     *                                                           on the default transactional stream.
     */
    public function deleteSuppressions(
        array $suppressionChanges = [],
        string $messageStream = 'outbound',
    ): DynamicResponseModel;

    /**
     * List Suppressions that match the provided query parameters.
     *
     * @param string      $messageStream     Filter Suppressions by MessageStream. If not provided, Suppressions for
     *                                       the default transactional stream will be returned. (optional)
     * @param string|null $suppressionReason Filter Suppressions by reason. E.g.: HardBounce, SpamComplaint,
     *                                       ManualSuppression. (optional)
     * @param string|null $origin            Filter Suppressions by the origin that created them. E.g.: Customer,
     *                                       Recipient, Admin. (optional)
     * @param string|null $fromDate          Filter suppressions from the date specified - inclusive. (optional)
     * @param string|null $toDate            Filter suppressions up to the date specified - inclusive. (optional)
     * @param string|null $emailAddress      Filter by a specific email address. (optional)
     */
    public function getSuppressions(
        string $messageStream = 'outbound',
        string|null $suppressionReason = null,
        string|null $origin = null,
        string|null $fromDate = null,
        string|null $toDate = null,
        string|null $emailAddress = null,
    ): DynamicResponseModel;

    /**
     * Delete a template.
     *
     * @param string|int $id The ID or alias of the template to delete.
     * @psalm-param TemplateId $id
     */
    public function deleteTemplate($id): DynamicResponseModel; // phpcs:ignore

    /**
     * Create a template
     *
     * @param string      $name           The friendly name for this template.
     * @param string      $subject        The template to be used for the 'subject' of emails sent using this template.
     * @param string      $htmlBody       The template to be used for the 'htmlBody' of emails sent using this template,
     *                                    optional if 'textBody' is not NULL.
     * @param string      $textBody       The template to be used for the 'textBody' of emails sent using this template,
     *                                    optional if 'htmlBody' is not NULL.
     * @param string|null $alias          An optional string you can provide to identify this Template. Allowed
     *                                    characters are numbers, ASCII letters, and ‘.’, ‘-’, ‘_’ characters, and the
     *                                    string has to start with a letter.
     * @param string      $templateType   Creates the template based on the template type provided. Possible options:
     *                                    Standard or Layout. Defaults to Standard.
     * @param string|null $layoutTemplate The alias of the Layout template that you want to use as layout for this
     *                                    Standard template. If not provided, a standard template will not use a layout
     *                                    template.
     */
    public function createTemplate(
        string $name,
        string $subject,
        string $htmlBody,
        string $textBody,
        string|null $alias = null,
        string $templateType = 'Standard',
        string|null $layoutTemplate = null,
    ): DynamicResponseModel;

    /**
     * Edit a template
     *
     * @param TemplateId  $id             The ID or alias of the template you wish to update.
     * @param string|null $name           The friendly name for this template.
     * @param string|null $subject        The template to be used for the 'subject' of emails sent using this template.
     * @param string|null $htmlBody       The template to be used for the 'htmlBody' of emails sent using this template.
     * @param string|null $textBody       The template to be used for the 'textBody' of emails sent using this template.
     * @param string|null $alias          An optional string you can provide to identify this Template. Allowed
     *                                    characters are numbers, ASCII letters, and ‘.’, ‘-’, ‘_’ characters, and the
     *                                    string has to start with a letter.
     * @param string|null $layoutTemplate The alias of the Layout template that you want to use as layout for this
     *                                    Standard template. If not provided, a standard template will not use a layout
     *                                    template.
     */
    public function editTemplate(
        $id,
        string|null $name = null,
        string|null $subject = null,
        string|null $htmlBody = null,
        string|null $textBody = null,
        string|null $alias = null,
        string|null $layoutTemplate = null,
    ): DynamicResponseModel;

    /**
     * Get the current information for a specific template.
     *
     * @param string|int $id the Id or alias for the template info you wish to retrieve.
     * @psalm-param TemplateId $id
     */
    public function getTemplate($id): DynamicResponseModel; // phpcs:ignore

    /**
     * Get all templates associated with the Server.
     *
     * @param int         $count          The total number of templates to get at once (default is 100)
     * @param int         $offset         The number of templates to "Skip" before returning results.
     * @param string      $templateType   Filters the results based on the template type provided. Possible options:
     *                                    Standard, Layout, All. Defaults to All.
     * @param string|null $layoutTemplate Filters the results based on the layout template alias. Defaults to NULL.
     */
    public function listTemplates(
        int $count = 100,
        int $offset = 0,
        string $templateType = 'All',
        string|null $layoutTemplate = null,
    ): DynamicResponseModel;

    /**
     * Confirm that your template content can be parsed/rendered…
     * …get a test rendering of your template, and a suggested model to use with your templates.
     *
     * @param string|null        $subject         The Subject template you wish to test.
     * @param string|null        $htmlBody        The HTML template you wish to test
     * @param string|null        $textBody        The number of templates to "Skip" before returning results.
     * @param TemplateModel|null $testRenderModel The model to be used when doing test renders of the templates
     *                                            that successfully parse in this request.
     * @param bool               $inlineCss       If htmlBody is specified, the test render will automatically do
     *                                            CSS Inlining for the HTML content. You may opt-out of this
     *                                            behavior by passing 'false' for this parameter.
     * @param string             $templateType    Validates templates based on template type (layout template or
     *                                            standard template). Possible options: Standard or Layout.
     *                                            Defaults to Standard.
     * @param string|null        $layoutTemplate  An optional string to specify which layout template alias to use
     *                                            to validate a standard template. If not provided a standard
     *                                            template will not use a layout template.
     */
    public function validateTemplate(
        string|null $subject = null,
        string|null $htmlBody = null,
        string|null $textBody = null,
        $testRenderModel = null,
        bool $inlineCss = true,
        string $templateType = 'Standard',
        string|null $layoutTemplate = null,
    ): DynamicResponseModel;

    /**
     * Get information about a specific webhook configuration.
     *
     * @param int $id The Id of the webhook configuration you wish to retrieve.
     */
    public function getWebhookConfiguration(int $id): DynamicResponseModel;

    /**
     * Get all webhook configurations associated with the Server.
     *
     * @param string|null $messageStream Optional message stream to filter results by. If not provided,
     *                                   all configurations for the server will be returned.
     */
    public function getWebhookConfigurations(string|null $messageStream = null): DynamicResponseModel;

    /**
     * Delete a webhook configuration.
     *
     * @param int $id The Id of the webhook configuration you wish to delete.
     */
    public function deleteWebhookConfiguration(int $id): DynamicResponseModel;

    /**
     * Create a webhook configuration.
     *
     * @param string                            $url           The webhook URL.
     * @param string|null                       $messageStream Message stream this configuration should belong to.
     *                                                         If not provided, it will belong to the default
     *                                                         transactional stream.
     * @param HttpAuth|null                     $httpAuth      Optional Basic HTTP Authentication.
     * @param HeaderList|null                   $httpHeaders   Optional list of custom HTTP headers.
     * @param WebhookConfigurationTriggers|null $triggers      Optional triggers for this webhook configuration.
     */
    public function createWebhookConfiguration(
        string $url,
        string|null $messageStream = null,
        HttpAuth|null $httpAuth = null,
        array|null $httpHeaders = null,
        WebhookConfigurationTriggers|null $triggers = null,
    ): DynamicResponseModel;

    /**
     * Edit a webhook configuration.
     *
     * Any parameters passed with NULL will be ignored (their existing values will not be modified).
     *
     * @param int                               $id          The Id of the webhook configuration you wish to edit.
     * @param string|null                       $url         Optional webhook URL.
     * @param HttpAuth|null                     $httpAuth    Optional Basic HTTP Authentication.
     * @param HeaderList|null                   $httpHeaders Optional list of custom HTTP headers.
     * @param WebhookConfigurationTriggers|null $triggers    Optional triggers for this webhook configuration.
     */
    public function editWebhookConfiguration(
        int $id,
        string|null $url = null,
        HttpAuth|null $httpAuth = null,
        array|null $httpHeaders = null,
        WebhookConfigurationTriggers|null $triggers = null,
    ): DynamicResponseModel;
}
