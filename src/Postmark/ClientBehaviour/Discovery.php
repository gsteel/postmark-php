<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Postmark\Exception\DiscoveryFailure;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Throwable;

/** @internal Postmark */
trait Discovery
{
    /** @throws DiscoveryFailure */
    private static function resolveHttpClient(ClientInterface|null $client): ClientInterface
    {
        if ($client) {
            return $client;
        }

        try {
            return Psr18ClientDiscovery::find();
        } catch (Throwable $error) {
            throw DiscoveryFailure::clientDiscoveryFailed($error);
        }
    }

    /** @throws DiscoveryFailure */
    private static function resolveRequestFactory(): RequestFactoryInterface
    {
        try {
            return Psr17FactoryDiscovery::findRequestFactory();
        } catch (Throwable $error) {
            throw DiscoveryFailure::requestFactoryDiscoveryFailed($error);
        }
    }

    /** @throws DiscoveryFailure */
    private static function resolveStreamFactory(): StreamFactoryInterface
    {
        try {
            return Psr17FactoryDiscovery::findStreamFactory();
        } catch (Throwable $error) {
            throw DiscoveryFailure::streamFactoryDiscoveryFailed($error);
        }
    }

    /** @throws DiscoveryFailure */
    private static function resolveUriFactory(): UriFactoryInterface
    {
        try {
            return Psr17FactoryDiscovery::findUriFactory();
        } catch (Throwable $error) {
            throw DiscoveryFailure::uriFactoryDiscoveryFailed($error);
        }
    }
}
