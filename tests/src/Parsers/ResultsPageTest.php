<?php

namespace Sportic\Omniresult\Timeit\Tests\Parsers;

use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;
use Sportic\Omniresult\Timeit\Parsers\ResultsPage as PageParser;
use Sportic\Omniresult\Timeit\Scrapers\ResultsPage as PageScraper;

use function PHPUnit\Framework\assertInstanceOf;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\Timeit\Tests\Scrapers
 */
class ResultsPageTest extends AbstractPageTest
{

    public function testGenerateContentResultList()
    {
        $scraper = new PageScraper();
        $scraper->setParameter('categoryName', 'Cat1');

        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            $scraper,
            'ResultsPage/SimpleTable/page'
        );

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(50, $results);
        self::assertInstanceOf(Result::class, $results[5]);
        self::assertEquals(
            [
                'posGen' => '6',
                'posGender' => '6',
                'posCategory' => '2',
                'bib' => '5',
                'fullName' => 'Codrea Gheorghe',
                'href' => null,
                'time' => '01:01:23',
                'category' => 'Cat1',
                'gender' => 'male',
                'id' => null,
                'parameters' => null,
                'splits' => [],
                'status' => null,
                'country' => null,
                'club' => null,
                'firstName' => 'Gheorghe',
                'lastName' => 'Codrea',
                'timeGross' => null,
                'notes' => null,
                'dob' => null,
                'yob' => null,
                'result' => null,
            ],
            $results[5]->__toArray()
        );
    }

    public function testGenerateContentAll()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/SimpleTable/page'
        );
        $parametersSerialized = static::getParametersFixtures('ResultsPage/SimpleTable/page');

        self::assertEquals($parametersSerialized, $parametersParsed);
    }

    public function test_table_withDiploma()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/TableWithDiploma/page'
        );

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(246, $results);

        $result = $results[5];
        self::assertInstanceOf(Result::class, $result);
        self::assertEquals('12', $result->getPosGen());
        self::assertEquals('12', $result->getPosGender());
        self::assertEquals('6', $result->getPosCategory());
    }

    public function test_table_withSosireCP()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/TableWithSosireCP/page'
        );

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(165, $results);

        $result = $results[5];
        self::assertInstanceOf(Result::class, $result);
        self::assertEquals('6', $result->getPosGender());
        self::assertEquals('male', $result->getGender());
        self::assertEquals('14:13:03', $result->getTime());
    }

    public function test_table_withTimpSosire()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/TimpSosire/page'
        );

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(15, $results);

        $result = $results[5];
        self::assertInstanceOf(Result::class, $result);
        self::assertEquals('6', $result->getPosGender());
        self::assertEquals('male', $result->getGender());
        self::assertEquals('2:23:34', $result->getTime());
    }

    public function test_table_withTimpFinish()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/TimpFinish/page'
        );

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(162, $results);

        $result = $results[5];
        self::assertInstanceOf(Result::class, $result);
        self::assertEquals('6', $result->getPosGender());
        self::assertEquals('male', $result->getGender());
        self::assertEquals('00:22:02', $result->getTime());
    }

    public function test_table_withTimpFinishSplits()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/TimpFinishSplits/page'
        );

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(62, $results);

        $result = $results[5];
        self::assertInstanceOf(Result::class, $result);
        self::assertEquals('6', $result->getPosGender());
        self::assertEquals('male', $result->getGender());
        self::assertEquals('1:28:16', $result->getTime());

        $splits = $result->getSplits();
        self::assertCount(2, $splits);

        $splitOne = $splits[0];
        assertInstanceOf(Split::class, $splitOne);
        self::assertEquals('1:01:51', $splitOne->getTime());

        $splitTwo = $splits[1];
        assertInstanceOf(Split::class, $splitTwo);
        self::assertEquals('0:26:24', $splitTwo->getTime());
    }
    public function test_table_EmptyResults()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/EmptyResults/page'
        );

        /** @var array|Result[] $results */
        $results = $parametersParsed['records'];

        self::assertCount(18, $results);

        $result = $results[17];
        self::assertInstanceOf(Result::class, $result);
        self::assertEquals('94', $result->getPosGender());
        self::assertEquals('male', $result->getGender());
        self::assertEquals('03:45:07', $result->getTime());
    }
}
