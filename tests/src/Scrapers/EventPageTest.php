<?php

namespace Sportic\Omniresult\Timeit\Tests\Scrapers;

use PHPUnit\Framework\TestCase;
use Sportic\Omniresult\Timeit\Scrapers\EventPage as PageScraper;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\Timeit\Tests\Scrapers
 */
class EventPageTest extends TestCase
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'https://time-it.ro/2020/parang/',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        $html =  $crawler->html();

        static::assertStringContainsString('Parang Night Challenge 2020', $html);
        static::assertStringContainsString('Alergare', $html);
        static::assertStringContainsString('Ski', $html);
    }

//    public function testgenerateFixture()
//    {
//        $params = ['eventSlug' => '2016/CorcovaTrailRace'];
//        $scraper = new PageScraper();
//        $scraper->initialize($params);
//        $crawler= $scraper->getCrawler();
//
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/EventPage/MultipleRaceCategoriesByClass/page.html', $crawler->html());
//    }


    /**
     * @return Crawler
     */
    protected function getCrawler()
    {
        $params = ['eventSlug' => '2020/parang'];
        $scraper = new PageScraper();
        $scraper->initialize($params);
        return $scraper->getCrawler();
    }
}
