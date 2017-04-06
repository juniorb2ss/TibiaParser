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
use Mockery;

/**
*
*/
class PlayerCrawlerTest extends TestCase
{
   
    protected $history;

    protected function getMockGuzzle(array $responses = [])
    {
        if (empty($responses)) {
            $responses = [new GuzzleResponse(200, [], file_get_contents(__DIR__ . '/../stubs/player.html'))];
        }
        
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $this->history = [];
        $handlerStack->push(Middleware::history($this->history));
        $guzzle = new GuzzleClient(array('redirect.disable' => true, 'base_uri' => '', 'handler' => $handlerStack));
        return $guzzle;
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testPlayerInformation()
    {
        $playerName = 'Themacho';

        $crawlerMock  = Mockery::mock(Crawler::class)->makePartial();
        $tibiaParserMock = Mockery::mock(TibiaParser::class)->makePartial();

        $mockGuzzle = $this->getMockGuzzle();
        $crawlerMock->shouldReceive('getGuzzleClient')->andReturn($mockGuzzle);
        $tibiaParserMock->shouldReceive('getCrawler')->andReturn(
            $crawlerMock
        );

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

        $player = $tibiaParserMock->player($playerName);

        $this->assertEquals(
            $expectedInformations,
            $player->getPlayerArrayInformations()
        );
    }
}
