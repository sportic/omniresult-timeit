<?php

namespace Sportic\Omniresult\Timeit\Tests\Parsers;

use Sportic\Omniresult\Common\Content\GenericContent;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Content\RecordContent;
use Sportic\Omniresult\Timeit\Parsers\AbstractParser;
use Sportic\Omniresult\Timeit\Scrapers\AbstractScraper;
use Sportic\Omniresult\Timeit\Parsers\EventPage as EventPageParser;
use Sportic\Omniresult\Timeit\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractPageTest
 * @package Sportic\Omniresult\Timeit\Tests\Scrapers
 */
abstract class AbstractPageTest extends AbstractTest
{
    protected static $parameters;

    /**
     * @var GenericContent|ListContent|RecordContent
     */
    protected static $parametersParsed;

    /**
     * @param AbstractParser $parser
     * @param AbstractScraper $scrapper
     * @param $fixturePath
     * @return mixed
     */
    public static function initParserFromFixtures($parser, $scrapper, $fixturePath)
    {
        $crawler = new Crawler(null, $scrapper->getCrawlerUri());
        $crawler->addContent(
            file_get_contents(
                TEST_FIXTURE_PATH . DS . 'Parsers' . DS . $fixturePath . '.html'
            ),
            'text/html;charset=utf-8'
        );

        $parser->setScraper($scrapper);
        $parser->setCrawler($crawler);

        $parametersParsed = $parser->getContent();

//        file_put_contents(
//            TEST_FIXTURE_PATH . DS . 'Parsers' . DS . $fixturePath . '.serialized',
//            serialize($parser->getContent())
//        );

        return $parametersParsed;
    }

    /**
     * @param $fixturePath
     * @return mixed
     */
    public static function getParametersFixtures($fixturePath)
    {
        return unserialize(
            file_get_contents(TEST_FIXTURE_PATH . DS . 'Parsers' . DS . $fixturePath . '.serialized')
        );
    }
}
