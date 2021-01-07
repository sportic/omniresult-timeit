<?php

namespace Sportic\Omniresult\Timeit\Tests\Scrapers;

use PHPUnit\Framework\TestCase;
use Sportic\Omniresult\Timeit\Scrapers\ResultsPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ResultsPageTest
 * @package Sportic\Omniresult\Timeit\Tests\Scrapers
 */
class ResultsPageTest extends TestCase
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'https://www.trackmyrace.com/en/running/event-zone/ajax/event/cozia-mountain-run-6/resultstable/-bf626f0882/expanded/page/1/',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertContains('Constantin Mirica', $crawler->html());
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/event_page.html', $crawler->html());
    }

    /**
     * @return Crawler
     */
    protected function getCrawler()
    {
        $params = [
            'eventSlug' => 'cozia-mountain-run-6',
            'raceSlug' => '-bf626f0882',
            'page' => 2,
        ];
        $scraper = new ResultsPage();
        $scraper->initialize($params);
        return $scraper->getCrawler();
    }
}
