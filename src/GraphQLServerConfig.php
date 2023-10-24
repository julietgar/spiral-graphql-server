<?php

declare(strict_types=1);

namespace Idiosyncratic\Spiral\GraphQL\Server;

use Spiral\Core\InjectableConfig;

final class GraphQLServerConfig extends InjectableConfig
{
    public const CONFIG = 'graphql';

    /**
     * @var array{
     *   'schema_file': string
     * }
     */
    protected array $config = [
        'schema_file' => '',
    ];

    public function getSchemaFile() : string
    {
        return $this->config['schema_file'];
    }
}
