<?php

namespace Sportic\Omniresult\Timeit\Tests\Parsers;

use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\Timeit\Scrapers\EventPage as PageScraper;
use Sportic\Omniresult\Timeit\Parsers\EventPage as PageParser;

/**
 * Class EventPageTest
 * @package Sportic\Omniresult\Timeit\Tests\Scrapers
 */
class EventPageTest extends AbstractPageTest
{
    public function testGenerateContentRaces()
    {
        $parametersParsed = static::initParserFromFixtures(
            new PageParser(),
            (new PageScraper()),
            'EventPage/MultipleRaceCategories/page'
        );

        $records = $parametersParsed['records'];
        self::assertCount(11, $records);

        $fixtureParameters = static::getParametersFixtures('EventPage/MultipleRaceCategories/page');
        self::assertEquals($fixtureParameters, $parametersParsed);
    }
}
