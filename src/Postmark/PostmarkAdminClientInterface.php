<?php

declare(strict_types=1);

namespace Postmark;

use Postmark\Models\DynamicResponseModel;

/**
 * The PostmarkAdminClient allows users to access and modify "Account-wide" settings.
 *
 * At this time the API supports management of the "Sender Signatures", "Domains", and "Servers".
 * The constructor requires an Account Token. This token is NOT the same as a Server Token.
 * You can get your account token from this page: https://postmarkapp.com/account/edit
 *
 * @link https://postmarkapp.com/account/edit
 *
 * phpcs:disable SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming
 */
interface PostmarkAdminClientInterface
{
    /**
     * Request a given server by ID.
     *
     * @param int $id The Id for the server you wish to retrieve.
     */
    public function getServer(int $id): DynamicResponseModel;

    /**
     * Get a list of all servers configured on the account.
     *
     * @param int         $count  The number of servers to retrieve in the request, defaults to 100.
     * @param int         $offset The number of servers to "skip" when paging through lists of servers.
     * @param string|null $name   Filter by server name.
     */
    public function listServers(int $count = 100, int $offset = 0, string|null $name = null): DynamicResponseModel;

    /**
     * Delete a Server used for sending/receiving mail. NOTE: To protect your account, you'll need to
     * contact support and request that they enable this feature on your account before you can use this
     * client to delete Servers.
     *
     * @param int $id The ID of the Server to delete.
     */
    public function deleteServer(int $id): DynamicResponseModel;

    /**
     * Modify an existing Server. Any parameters passed with NULL will be
     * ignored (their existing values will not be modified).
     *
     * @param int         $id                      The ID of the Server we wish to modify.
     * @param string|null $name                    Set the name of the server.
     * @param string|null $color                   Set the color for the server in the Postmark WebUI (must be:
     *                                             'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param bool|null   $rawEmailEnabled         Enable raw email to be sent with inbound.
     * @param bool|null   $smtpApiActivated        Specifies whether SMTP is enabled on this server.
     * @param string|null $inboundHookUrl          URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl           URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl             URL to POST to everytime an open event occurs.
     * @param bool|null   $postFirstOpenOnly       If set to true, only the first open by a particular recipient will
     *                                             initiate the open webhook. Any subsequent opens of the same email
     *                                             by the same recipient will not initiate the webhook.
     * @param bool|null   $trackOpens              Indicates if all emails being sent through this server have
     *                                             open tracking enabled.
     * @param string|null $inboundDomain           Inbound domain for MX setup.
     * @param int|null    $inboundSpamThreshold    The maximum spam score for an inbound message before
     *                                             it's blocked (range from 0-30).
     * @param string|null $trackLinks              Indicates if all emails being sent through this server have
     *                                             link tracking enabled.
     * @param string|null $clickHookUrl            URL to POST to everytime a click event occurs.
     * @param string|null $deliveryHookUrl         URL to POST to everytime a click event occurs.
     * @param bool|null   $enableSmtpApiErrorHooks Specifies whether SMTP API Errors will be included
     *                                             with bounce webhooks.
     */
    public function editServer(
        int $id,
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
        bool|null $enableSmtpApiErrorHooks = null,
    ): DynamicResponseModel;

    /**
     * Create a new Server. Any parameters passed with NULL will be
     * ignored (their default values will be used).
     *
     * @param string      $name                    Set the name of the server.
     * @param string|null $color                   Set the color for the server in the Postmark WebUI
     *                                             (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow',
     *                                              or 'grey')
     * @param bool|null   $rawEmailEnabled         Enable raw email to be sent with inbound.
     * @param bool|null   $smtpApiActivated        Specifies whether or not SMTP is enabled on this server.
     * @param string|null $inboundHookUrl          URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl           URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl             URL to POST to everytime an open event occurs.
     * @param bool|null   $postFirstOpenOnly       If set to true, only the first open by a particular recipient will
     *                                             initiate the open webhook. Any subsequent opens of the same email
     *                                             by the same recipient will not initiate the webhook.
     * @param bool|null   $trackOpens              Indicates if all emails being sent through this server have
     *                                             open tracking enabled.
     * @param string|null $inboundDomain           Inbound domain for MX setup.
     * @param int|null    $inboundSpamThreshold    The maximum spam score for an inbound message before
     *                                             it's blocked (range from 0-30).
     * @param string|null $trackLinks              Indicates if all emails being sent through this server
     *                                             have link tracking enabled.
     * @param string|null $clickHookUrl            URL to POST to everytime an click event occurs.
     * @param string|null $deliveryHookUrl         URL to POST to everytime an click event occurs.
     * @param bool|null   $enableSmtpApiErrorHooks Specifies whether or not SMTP API Errors will be included
     *                                             with bounce webhooks.
     */
    public function createServer(
        string $name,
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
        bool|null $enableSmtpApiErrorHooks = null,
    ): DynamicResponseModel;

    /**
     * Get a "page" of Sender Signatures.
     *
     * @param int $count  The number of Sender Signatures to retrieve with this request.
     * @param int $offset The number of Sender Signatures to 'skip' when 'paging' through them.
     */
    public function listSenderSignatures(int $count = 100, int $offset = 0): DynamicResponseModel;

    /**
     * Get information for a specific Sender Signature.
     *
     * @param int $id The ID for the Sender Signature you wish to retrieve.
     */
    public function getSenderSignature(int $id): DynamicResponseModel;

    /**
     * Create a new Sender Signature for a given email address. Note that you will need to
     * "verify" this Sender Signature by following a link that will be emailed to the "fromEmail"
     * address specified when calling this method.
     *
     * @param string      $fromEmail        The email address for the Sender Signature
     * @param string      $name             The name of the Sender Signature.
     * @param string|null $replyToEmail     The reply-to email address for the Sender Signature.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     */
    public function createSenderSignature(
        string $fromEmail,
        string $name,
        string|null $replyToEmail = null,
        string|null $returnPathDomain = null,
    ): DynamicResponseModel;

    /**
     * Alter the defaults for a Sender Signature.
     *
     * @param int         $id               The ID for the Sender Signature we wish to modify.
     * @param string|null $name             The name of the Sender Signature.
     * @param string|null $replyToEmail     The reply-to email address for the Sender Signature.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     */
    public function editSenderSignature(
        int $id,
        string|null $name = null,
        string|null $replyToEmail = null,
        string|null $returnPathDomain = null,
    ): DynamicResponseModel;

    /**
     * Delete a Sender Signature with the given ID.
     *
     * @param int $id The ID for the Sender Signature we wish to delete.
     */
    public function deleteSenderSignature(int $id): DynamicResponseModel;

    /**
     * Cause a new verification email to be sent for an existing (unverified) Sender Signature.
     * Sender Signatures require verification before they may be used to send email through the Postmark API.
     *
     * @param int $id The ID for the Sender Signature to which we wish to resend a verification email.
     */
    public function resendSenderSignatureConfirmation(int $id): DynamicResponseModel;

    /**
     * Get a "page" of Domains.
     *
     * @param int $count  The number of Domains to retrieve with this request.
     * @param int $offset The number of Domains to 'skip' when 'paging' through them.
     */
    public function listDomains(int $count = 100, int $offset = 0): DynamicResponseModel;

    /**
     * Get information for a specific Domain.
     *
     * @param int $id The ID for the Domains you wish to retrieve.
     */
    public function getDomain(int $id): DynamicResponseModel;

    /**
     * Create a new Domain with the given Name.
     *
     * @param string      $name             The name of the Domain.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     */
    public function createDomain(string $name, string|null $returnPathDomain = null): DynamicResponseModel;

    /**
     * Alter the properties of a Domain.
     *
     * @param int         $id               The ID for the Domain we wish to modify.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Domain.
     */
    public function editDomain(int $id, string|null $returnPathDomain = null): DynamicResponseModel;

    /**
     * Delete a Domain with the given ID.
     *
     * @param int $id The ID for the Domain we wish to delete.
     */
    public function deleteDomain(int $id): DynamicResponseModel;

    /**
     * Request that the Postmark API verify the SPF records associated
     * with the Domain. Configuring SPF is not required to use
     * Postmark, but it is highly recommended, and can improve delivery rates.
     *
     * @param int $id The ID for the Domain for which we wish to verify the SPF records.
     */
    public function verifyDomainSPF(int $id): DynamicResponseModel;

    /**
     * Rotate DKIM keys associated with the Domain. This key must be added
     * to your DNS records. Including DKIM is not required, but is recommended. For more information
     * on DKIM and its purpose, see http://www.dkim.org/
     *
     * @param int $id The ID for the Domain for which we wish to get an updated DKIM configuration.
     */
    public function rotateDKIMForDomain(int $id): DynamicResponseModel;
}
