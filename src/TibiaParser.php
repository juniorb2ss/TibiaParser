<?php
namespace juniorb2ss\TibiaParser;

use juniorb2ss\TibiaParser\Crawlers\PlayerCrawler;
use juniorb2ss\TibiaParser\Crawlers\Crawler;

/**
*
*/
class TibiaParser
{
    /**
     * [getCrawler description]
     * @return juniorb2ss\TibiaParser\Crawlers\Crawler
     */
    protected function getCrawler()
    {
        return new Crawler;
    }

    /**
     * [player description]
     * @return \juniorb2ss\TibiaParser\Crawlers\PlayerCrawler
     */
    public function player()
    {
        return new PlayerCrawler(
            $this->getCrawler()
        );
    }
}
