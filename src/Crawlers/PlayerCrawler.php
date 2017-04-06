<?php

namespace juniorb2ss\TibiaParser\Crawlers;

use Goutte\Client;

use juniorb2ss\TibiaParser\Contracts\PlayerCrawlerInterface;
use juniorb2ss\TibiaParser\Crawlers\Crawler;
use Waavi\Sanitizer\Sanitizer;

/**
*
*/
class PlayerCrawler implements PlayerCrawlerInterface
{
   
    /**
     * Crawler Interface
     * @var juniorb2ss\TibiaParser\Crawlers\Crawler
     */
    public $crawler;

    /**
     * [__construct description]
     * @param Crawler $crawler [description]
     */
    public function __construct(Crawler $crawler, $playerName)
    {
        $this->crawler = $crawler;

        $this->bodyCrawled = $this->makeRequest($playerName);

        $this->getPlayer($playerName);
    }

    /**
     * Scrap selectors
     * @var array
     */
    private $xPaths = [
        'name' =>  '//div[@class="BoxContent"]/table[1]/tr[2]/td[2]',
        'sex' =>  '//div[@class="BoxContent"]/table[1]/tr[3]/td[2]',
        'vocation' =>  '//div[@class="BoxContent"]/table[1]/tr[4]/td[2]',
        'level' =>  '//div[@class="BoxContent"]/table[1]/tr[5]/td[2]',
        'achievement_points' =>  '//div[@class="BoxContent"]/table[1]/tr[6]/td[2]',
        'world' =>  '//div[@class="BoxContent"]/table[1]/tr[7]/td[2]',
        'residence' =>  '//div[@class="BoxContent"]/table[1]/tr[8]/td[2]',
        'guild_membership' =>  '//div[@class="BoxContent"]/table[1]/tr[9]/td[2]',
        'last_login' =>  '//div[@class="BoxContent"]/table[1]/tr[10]/td[2]',
        'comment' =>  '//div[@class="BoxContent"]/table[1]/tr[11]/td[2]',
        'account_status' =>  '//div[@class="BoxContent"]/table[1]/tr[12]/td[2]',
    ];

    /**
     * [$filters description]
     * @var [type]
     */
    protected $filters = [
        'name' => 'trim',
        'sex' => 'trim',
        'vocation' => 'trim',
        'achievement_points' => 'trim',
        'world' => 'trim',
        'residence' => 'trim',
        'guild_membership' => 'trim',
        'last_login' => '',
        'comment' => 'trim',
        'account_status' => 'trim'
    ];

    /**
     * Url to crawler
     * @var string
     */
    private $url = 'https://secure.tibia.com/community/?subtopic=characters&name=%s';

    /**
     * [$scraping description]
     * @var array
     */
    protected $scraping = [];

    /**
     * [$method description]
     * @var string
     */
    private $method = 'GET';

    /**
     * [makeRequest description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function makeRequest($name)
    {
        $client = $scrap = $this->crawler->getClient();
        $crawler = $client->request($this->method, sprintf($this->url, $name));

        return $crawler;
    }

    /**
     * [getPlayer description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    protected function getPlayer($name)
    {
        foreach ($this->xPaths as $attribute => $xPath) {
            $node = $this->bodyCrawled->filterXPath(
                $xPath
            );

            $this->scraping[$attribute] = str_replace('&nbsp;', ' ', htmlentities($node->text(), null, 'utf-8'));
        }

        $sanitizer  = new Sanitizer($this->scraping, $this->filters);
        $this->scraping = $sanitizer->sanitize();

        return $this;
    }

    /**
     * [getPlayerArrayInformations description]
     * @return [type] [description]
     */
    public function getPlayerArrayInformations()
    {
        return $this->scraping;
    }
}
