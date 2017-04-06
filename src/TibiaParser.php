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
     * @codeCoverageIgnore
     * @return juniorb2ss\TibiaParser\Crawlers\Crawler
     */
    public function getCrawler()
    {
        return new Crawler;
    }

    /**
     * [player description]
     * @return [type] [description]
     */
    public function player($name)
    {
        return new PlayerCrawler($this->getCrawler(), $name);
    }
}
