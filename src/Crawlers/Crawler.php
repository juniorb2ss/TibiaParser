<?php

namespace juniorb2ss\TibiaParser\Crawlers;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

use juniorb2ss\TibiaParser\Contracts\CrawlerInterface;

/**
*
*/
class Crawler implements CrawlerInterface
{
    /**
     * [getClient description]
     * @return [type] [description]
     */
    public function getClient()
    {
        $client = new Client();
        $guzzle = $this->getGuzzleClient();
        $client->setClient($guzzle);

        return $client;
    }

    /**
     * @codeCoverageIgnore
     * [getGuzzleClient description]
     * @return [type] [description]
     */
    public function getGuzzleClient()
    {
        return new GuzzleClient();
    }
}
