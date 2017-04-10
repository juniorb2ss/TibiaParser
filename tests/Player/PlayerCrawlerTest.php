<?php

namespace juniorb2ss\TibiaParser\Player;

use juniorb2ss\TibiaParser\TibiaParser;
use juniorb2ss\TibiaParser\TestCase;
use juniorb2ss\TibiaParser\Crawlers\PlayerCrawler;
use juniorb2ss\TibiaParser\Crawlers\Crawler;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Client as GuzzleClient;
use juniorb2ss\TibiaParser\Contracts\CrawlerInterface;
use Mockery;
use Carbon\Carbon;

/**
*
*/
class PlayerCrawlerTest extends TestCase
{
   
    protected $history;

    protected $stubFile = __DIR__ . '/../stubs/player.html';

    public function setUp()
    {
        $crawlerMock  = Mockery::mock(Crawler::class)
                        ->makePartial()
                        ->shouldReceive('getGuzzleClient')
                        ->andReturn($this->getMockGuzzle())
                        ->getMock();

        $tibiaParserMock = Mockery::mock(TibiaParser::class)->makePartial();
        
        $playerCrawlerMock = Mockery::mock(PlayerCrawler::class, array($crawlerMock))->makePartial();

        $tibiaParserMock
            ->shouldReceive('player')
            ->andReturn($playerCrawlerMock);

        $this->tibia = $tibiaParserMock;
        $this->player = $tibiaParserMock->player();
    }

    protected function getMockGuzzle(array $responses = [])
    {
        if (empty($responses)) {
            $responses = [new GuzzleResponse(200, [], file_get_contents($this->stubFile))];
        }
        
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $this->history = [];
        $handlerStack->push(Middleware::history($this->history));
        $guzzle = new GuzzleClient(array('redirect.disable' => true, 'base_uri' => '', 'handler' => $handlerStack));
        return $guzzle;
    }

    public function testPlayerHasLastLogin()
    {
        $player = $this->player
            ->getPlayer('Themacho')
            ->getAllInformationsFromPlayer();
            
        $this->assertInstanceOf(
            Carbon::class,
            $player->getPlayerLastLogin()
        );
    }

    public function testSetCrawler()
    {
        $crawler = new Crawler;

        $this->player->setCrawlerInstance($crawler);

        $this->assertInstanceOf(CrawlerInterface::class, $this->player->getCrawlerInstance());
    }

    public function testPlayerInformation()
    {
        $playerName = 'Themacho';
        $expectedInformations = [
            'name' => $playerName,
            'sex' => 'male',
            'vocation' => 'Master Sorcerer',
            'level' => '691',
            'achievement_points' => '376',
            'world' => 'Amera',
            'residence' => 'Roshamuul',
            'guild_membership' => 'Lord of the House Targaryen',
            'last_login' => 'Apr 06 2017, 03:33:33 CEST',
            'comment' => 'Macho macho man!',
            'account_status' => 'Premium Account'
        ];

        $player = $this->player
            ->getPlayer('Themacho')
            ->getAllInformationsFromPlayer();

        $this->assertEquals(
            $expectedInformations,
            $player->getPlayerArrayInformations()
        );
    }
}
