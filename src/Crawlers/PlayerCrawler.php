<?php

namespace juniorb2ss\TibiaParser\Crawlers;

use Goutte\Client;

use juniorb2ss\TibiaParser\Contracts\PlayerCrawlerInterface;
use juniorb2ss\TibiaParser\Crawlers\Crawler;
use Waavi\Sanitizer\Sanitizer;
use juniorb2ss\TibiaParser\Contracts\CrawlerInterface;
use Carbon\Carbon;

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
     * @codeCoverageIgnore
     * @param \juniorb2ss\TibiaParser\Contracts\CrawlerInterface $crawler [description]
     */
    public function __construct(CrawlerInterface $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * [getPlayer description]
     * @param  [type]  $playerName [description]
     * @param  boolean $force      [description]
     * @return [type]              [description]
     */
    public function getPlayer($playerName, $force = false)
    {
        if (empty($this->bodyCrawled) || $force === true) {
            $this->bodyCrawled = $this->makeRequest($playerName);
        }

        return $this;
    }

    /**
     * [setCrawlerInstance description]
     * @param CrawlerInterface $crawler [description]
     */
    public function setCrawlerInstance(CrawlerInterface $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * [getCrawlerInstance description]
     * @return CrawlerInterface [description]
     */
    public function getCrawlerInstance()
    {
        return $this->crawler;
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
     * @param  [type] $playerName [description]
     * @return [type]             [description]
     */
    public function makeRequest($playerName)
    {
        $client = $scrap = $this->crawler->getClient();
        $crawler = $client->request($this->method, sprintf($this->url, $playerName));

        return $crawler;
    }

    /**
     * [getAllInformationsFromPlayer description]
     * @return [type] [description]
     */
    public function getAllInformationsFromPlayer()
    {
        foreach ($this->xPaths as $attribute => $xPath) {
            $node = $this->bodyCrawled->filterXPath(
                $xPath
            );

            $this->scraping[$attribute] = null;

            if ($node->count()) {
                $this->scraping[$attribute] = str_replace('&nbsp;', ' ', htmlentities($node->text(), null, 'utf-8'));
            }
        }

        $sanitizer  = new Sanitizer($this->scraping, $this->filters);
        $this->scraping = $sanitizer->sanitize();

        return $this;
    }

    /**
     * [getScraping description]
     * @param  [type] $scrap [description]
     * @return string
     */
    public function getScraping($scrap)
    {
        return $this->hasScrap($scrap) ? $this->scraping[$scrap] : null;
    }

    /**
     * [hasScrap description]
     * @param  [type]  $scrap [description]
     * @return boolean        [description]
     */
    public function hasScrap($scrap)
    {
        return isset($this->scraping[$scrap]) && !empty($this->scraping[$scrap]);
    }

    /**
     * [getPlayerLastLogin description]
     * @codeCoverageIgnore
     * @return Carbon|Null
     */
    public function getPlayerLastLogin()
    {
        if ($this->hasScrap('last_login')) {
            return Carbon::parse(
                $this->getScraping('last_login')
            );
        }

        return null;
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
