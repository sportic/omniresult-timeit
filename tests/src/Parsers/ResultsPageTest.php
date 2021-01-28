<?php

namespace Sportic\Omniresult\Timeit\Tests\Parsers;

use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Timeit\Parsers\ResultsPage as PageParser;
use Sportic\Omniresult\Timeit\Scrapers\ResultsPage as PageScraper;

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
                'notes' => null
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
        self::assertSame('12', $result->getPosGen());
        self::assertSame('12', $result->getPosGender());
        self::assertSame('6', $result->getPosCategory());
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
        self::assertSame('6', $result->getPosGender());
        self::assertSame('male', $result->getGender());
        self::assertSame('14:13:03', $result->getTime());
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
        self::assertSame('6', $result->getPosGender());
        self::assertSame('male', $result->getGender());
        self::assertSame('2:23:34', $result->getTime());
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
        self::assertSame('94', $result->getPosGender());
        self::assertSame('male', $result->getGender());
        self::assertSame('03:45:07', $result->getTime());
    }
}
