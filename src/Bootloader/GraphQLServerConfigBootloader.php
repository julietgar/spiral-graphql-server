<?php

declare(strict_types=1);

namespace Idiosyncratic\Spiral\GraphQL\Server\Bootloader;

use GraphQL\Language\AST\TypeDefinitionNode;
use GraphQL\Type\Schema;
use GraphQL\Utils\BuildSchema;
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

    public function init() : void
    {
        $this->configurator->setDefaults(GraphQLServerConfig::CONFIG, ['schema_file' => '']);
    }

    public function boot(
        BinderInterface $binder,
    ) : void {
        $binder->bindSingleton(
            Schema::class,
            static function (
                EnvironmentInterface $env,
                GraphQLServerConfig $config,
                DirectoriesInterface $dir,
            ) : Schema {
                $contents = file_get_contents($config->getSchemaFile());

                $typeConfigDecorator = static function (
                    array $typeConfig,
                    TypeDefinitionNode $typeDefinitionNode,
                ) : array {
                    $name = $typeConfig['name'];

                    // ... add missing options to $typeConfig based on type $name
                    return $typeConfig;
                };

                return BuildSchema::build($contents, $typeConfigDecorator);
            },
        );
    }
}
