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
            'https://time-it.ro/2020/parang/?slug=C1M&head=off',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertStringContainsString('Palici Viorel', $crawler->html());
    }

//    public function testgenerateFixture()
//    {
//        $params = [
//            'eventSlug' => '2020/parang',
//            'categorySlug' => 'C1M'
//        ];
//        $scraper = new ResultsPage();
//        $scraper->initialize($params);
//        $crawler = $scraper->getCrawler();
//
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/SimpleTable/page.html', $crawler->html());
//    }

    /**
     * @return Crawler
     */
    protected function getCrawler()
    {
        $params = [
            'eventSlug' => '2020/parang',
            'categorySlug' => 'C1M',
        ];
        $scraper = new ResultsPage();
        $scraper->initialize($params);
        return $scraper->getCrawler();
    }
}
