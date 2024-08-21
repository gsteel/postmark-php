<?php

declare(strict_types=1);

namespace Postmark;

use Postmark\ClientBehaviour\PostmarkClientBase;
use Postmark\Models\DynamicResponseModel;

use function sprintf;

final class PostmarkAdminClient extends PostmarkClientBase implements PostmarkAdminClientInterface
{
    private const AUTH_HEADER_NAME = 'X-Postmark-Account-Token';

    protected function authorizationHeaderName(): string
    {
        return self::AUTH_HEADER_NAME;
    }

    public function getServer(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/servers/%s', $id)),
        );
    }

    public function listServers(int $count = 100, int $offset = 0, string|null $name = null): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/servers/', [
                'count' => $count,
                'offset' => $offset,
                'name' => $name,
            ]),
        );
    }

    public function deleteServer(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/servers/%s', $id)),
        );
    }

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
    ): DynamicResponseModel {
        $body = [];
        $body['name'] = $name;
        $body['color'] = $color;
        $body['rawEmailEnabled'] = $rawEmailEnabled;
        $body['smtpApiActivated'] = $smtpApiActivated;
        $body['inboundHookUrl'] = $inboundHookUrl;
        $body['bounceHookUrl'] = $bounceHookUrl;
        $body['openHookUrl'] = $openHookUrl;
        $body['postFirstOpenOnly'] = $postFirstOpenOnly;
        $body['trackOpens'] = $trackOpens;
        $body['inboundDomain'] = $inboundDomain;
        $body['inboundSpamThreshold'] = $inboundSpamThreshold;
        $body['trackLinks'] = $trackLinks;
        $body['ClickHookUrl'] = $clickHookUrl;
        $body['DeliveryHookUrl'] = $deliveryHookUrl;
        $body['EnableSmtpApiErrorHooks'] = $enableSmtpApiErrorHooks;

        $response = new DynamicResponseModel($this->processRestRequest('PUT', '/servers/' . $id, $body));
        $response['ID'] = $id;

        return $response;
    }

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
    ): DynamicResponseModel {
        $body = [];
        $body['name'] = $name;
        $body['color'] = $color;
        $body['rawEmailEnabled'] = $rawEmailEnabled;
        $body['smtpApiActivated'] = $smtpApiActivated;
        $body['inboundHookUrl'] = $inboundHookUrl;
        $body['bounceHookUrl'] = $bounceHookUrl;
        $body['openHookUrl'] = $openHookUrl;
        $body['postFirstOpenOnly'] = $postFirstOpenOnly;
        $body['trackOpens'] = $trackOpens;
        $body['inboundDomain'] = $inboundDomain;
        $body['inboundSpamThreshold'] = $inboundSpamThreshold;
        $body['trackLinks'] = $trackLinks;
        $body['ClickHookUrl'] = $clickHookUrl;
        $body['DeliveryHookUrl'] = $deliveryHookUrl;
        $body['EnableSmtpApiErrorHooks'] = $enableSmtpApiErrorHooks;

        return new DynamicResponseModel($this->processRestRequest('POST', '/servers/', $body));
    }

    public function listSenderSignatures(int $count = 100, int $offset = 0): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/senders/', [
                'count' => $count,
                'offset' => $offset,
            ]),
        );
    }

    public function getSenderSignature(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/senders/%s', $id)),
        );
    }

    public function createSenderSignature(
        string $fromEmail,
        string $name,
        string|null $replyToEmail = null,
        string|null $returnPathDomain = null,
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/senders/', [
                'fromEmail' => $fromEmail,
                'name' => $name,
                'replyToEmail' => $replyToEmail,
                'returnPathDomain' => $returnPathDomain,
            ]),
        );
    }

    public function editSenderSignature(
        int $id,
        string|null $name = null,
        string|null $replyToEmail = null,
        string|null $returnPathDomain = null,
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/senders/%s', $id), [
                'name' => $name,
                'replyToEmail' => $replyToEmail,
                'returnPathDomain' => $returnPathDomain,
            ]),
        );
    }

    public function deleteSenderSignature(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/senders/%s', $id)),
        );
    }

    public function resendSenderSignatureConfirmation(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/senders/%s/resend', $id)),
        );
    }

    public function listDomains(int $count = 100, int $offset = 0): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/domains/', [
                'count' => $count,
                'offset' => $offset,
            ]),
        );
    }

    public function getDomain(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/domains/%s', $id)),
        );
    }

    public function createDomain(string $name, string|null $returnPathDomain = null): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/domains/', [
                'returnPathDomain' => $returnPathDomain,
                'name' => $name,
            ]),
        );
    }

    public function editDomain(int $id, string|null $returnPathDomain = null): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/domains/%s', $id), ['returnPathDomain' => $returnPathDomain]),
        );
    }

    public function deleteDomain(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/domains/%s', $id)),
        );
    }

    public function verifyDomainSPF(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/domains/%s/verifyspf', $id)),
        );
    }

    public function rotateDKIMForDomain(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/domains/%s/rotatedkim', $id)),
        );
    }
}
