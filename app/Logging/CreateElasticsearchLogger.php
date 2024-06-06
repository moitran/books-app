<?php

namespace App\Logging;

use Elasticsearch\ClientBuilder;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Level;
use Monolog\Logger;

class CreateElasticsearchLogger
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('elasticsearch');
        //create the client
        $client = ClientBuilder::create()
            ->setHosts([$config['endpoint']])
            ->build();
        //create the handler
        $options = [
            'index' => $config['index'],
            'type' => '_doc',
        ];
        $handler = new ElasticsearchHandler($client, $options, Level::Info, true);

        $logger->setHandlers([$handler]);

        return $logger;
    }
}
