<?php

declare(strict_types=1);

namespace Idiosyncratic\Spiral\GraphQL\Server\Bootloader;

use GraphQL\Type\Schema;
use Idiosyncratic\GraphQL\Server\Operator;
use Idiosyncratic\GraphQL\Server\PsrMessageTransformer;
use Idiosyncratic\GraphQL\Server\Server as GraphQLServer;
use Idiosyncratic\GraphQL\Server\ServerConfig;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\BinderInterface;

class GraphQLServerBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        GraphQLServerConfigBootloader::class,
    ];

    public function boot(
        BinderInterface $binder,
    ) : void {
        $binder->bindSingleton(
            GraphQLServer::class,
            static function (
                Schema $schema,
                ResponseFactoryInterface $responseFactory,
                StreamFactoryInterface $streamFactory,
            ) : GraphQLServer {
                $config = new ServerConfig($schema);

                return new GraphQLServer(
                    new PsrMessageTransformer($responseFactory, $streamFactory),
                    new Operator($config),
                );
            },
        );
    }
}
