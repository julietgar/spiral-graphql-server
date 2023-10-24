<?php

declare(strict_types=1);

namespace Idiosyncratic\Spiral\GraphQL\Server\Bootloader;

use GraphQL\Type\Schema;
use Idiosyncratic\Spiral\GraphQL\Server\GraphQLServerConfig;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfigManager;
use Spiral\Core\BinderInterface;

use function Safe\file_get_contents;

final class GraphQLServerConfigBootloader extends Bootloader
{
    public function __construct(
        private readonly ConfigManager $configurator,
    ) {
    }

    public function init(
        EnvironmentInterface $env,
        DirectoriesInterface $directories,
    ) : void {
        $this->configurator->setDefaults(GraphQLServerConfig::CONFIG, [
            'schema_file' => $directories->get('app') . 'schema.graphql',
        ]);
    }

    public function boot(
        BinderInterface $binder,
        GraphQLServerConfig $config,
    ) : void {
        $binder->bindSingleton(
            Schema::class,
            static function (GraphQLServerConfig $config) : void {
                $schema = file_get_contents($config->getSchemaFile());
            }
        );
    }
}
