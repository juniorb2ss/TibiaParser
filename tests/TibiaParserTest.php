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
use juniorb2ss\TibiaParser\Contracts\PlayerCrawlerInterface;
use Mockery;

/**
*
*/
class TibiaParserTest extends TestCase
{
	public function setUp()
	{
		$this->tibia = new TibiaParser;
	}

	public function testGetCrawlerMethod()
	{
		$method = $this->getProtectedMethod(
			TibiaParser::class,
			null,
			'getCrawler'
		);

		$this->assertInstanceOf(
			CrawlerInterface::class, 
			$method->invoke($this->tibia)
		);
	}

	public function testPlayerMethod()
	{
		$this->assertInstanceOf(
			PlayerCrawlerInterface::class, 
			$this->tibia->player()
		);
	}
}