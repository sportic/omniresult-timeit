<?php

namespace Sportic\Omniresult\Timeit\Tests;

use Sportic\Omniresult\Timeit\RequestDetector;

/**
 * Class RequestDetectorTest
 * @package Sportic\Omniresult\Timeit\Tests
 */
class RequestDetectorTest extends AbstractTest
{
    /**
     * @param $url
     * @param $valid
     * @param $action
     * @param $params
     * @dataProvider detectProvider
     */
    public function testDetect($url, $valid, $action, $params)
    {
        $result = RequestDetector::detect($url);

        self::assertSame($valid, $result->isValid());
        self::assertSame($action, $result->getAction());
        self::assertSame($params, $result->getParams());
    }

    /**
     * @return array
     */
    public function detectProvider()
    {
        return [
            [
                'https://time-it.go.ro/2020/subcarpati/',
                true,
                'event',
                ['host' => 'time-it.go.ro', 'eventSlug' => '2020/subcarpati']
            ],
            [
                'https://time-it.go.ro/2019/CorcovaTrailRace/',
                true,
                'event',
                ['host' => 'time-it.go.ro', 'eventSlug' => '2019/CorcovaTrailRace']
            ],
            [
            'https://time-it.go.ro/2020/subcarpati/index.php?head=off&sort=c1f',
                true,
                'results',
                ['host' => 'time-it.go.ro', 'eventSlug' => '2020/subcarpati', 'slug' => 'c1f']
            ],
            [
            'https://time-it.go.ro/2020/IezerRun/index.php?head=off&sort=cm',
                true,
                'results',
                ['host' => 'time-it.go.ro', 'eventSlug' => '2020/IezerRun', 'slug' => 'cm']
            ],
            [
            'https://time-it.go.ro/2020/iasiintrail/index.php?head=off&sort=c1f',
                true,
                'results',
                ['host' => 'time-it.go.ro', 'eventSlug' => '2020/iasiintrail', 'slug' => 'c1f']
            ]
        ];
    }
}
