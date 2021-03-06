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
    public function test_getCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'https://time-it.ro/2020/parang/?sort=C1M&head=off',
            $crawler->getUri()
        );
    }

    public function test_getCrawlerUri_with_host()
    {
        $crawler = $this->getCrawler(
            [
                'host' => 'time-it.go.ro',
                'categorySlug' => null,
                'page' => 2,
                'categories' => [
                    'C1M' => 'Masculin',
                    'C2M' => '',
                    'S1M' => '',
                    'S2F' => 'Feminin',
                ]
            ]
        );

        static::assertSame(
            'https://time-it.go.ro/2020/parang/?sort=S2F&head=off',
            $crawler->getUri()
        );
    }

    public function test_getCrawlerUri_with_categories_array()
    {
        $crawler = $this->getCrawler(
            [
                'categorySlug' => null,
                'page' => 2,
                'categories' => [
                    'C1M' => 'Masculin',
                    'C2M' => '',
                    'S1M' => '',
                    'S2F' => 'Feminin',
                ]
            ]
        );

        static::assertSame(
            'https://time-it.ro/2020/parang/?sort=S2F&head=off',
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
//            'eventSlug' => '2018/urleaecotrail',
//            'categorySlug' => 'SM'
//        ];
//        $scraper = new ResultsPage();
//        $scraper->initialize($params);
//        $crawler = $scraper->getCrawler();
//
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/TimpSosire/page.html', $crawler->html());
//    }

    /**
     * @param array $params
     * @return Crawler
     */
    protected function getCrawler($params = [])
    {
        $paramsDefault = [
            'eventSlug' => '2020/parang',
            'categorySlug' => 'C1M',
        ];
        $scraper = new ResultsPage();
        $scraper->initialize(array_merge($paramsDefault, $params));
        return $scraper->getCrawler();
    }
}
