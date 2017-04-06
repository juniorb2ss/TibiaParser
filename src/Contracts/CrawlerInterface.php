<?php

namespace juniorb2ss\TibiaParser\Contracts;

interface CrawlerInterface
{
    /**
     * [getClient description]
     * @return [type] [description]
     */
    public function getClient();

    /**
     * [getGuzzleClient description]
     * @return [type] [description]
     */
    public function getGuzzleClient();
}
