<?php

namespace Sportic\Omniresult\Timeit;

use Sportic\Omniresult\Common\RequestDetector\HasDetectorTrait;
use Sportic\Omniresult\Common\TimingClient;
use Sportic\Omniresult\Trackmyrace\Scrapers\ResultPage;
use Sportic\Omniresult\Trackmyrace\Scrapers\ResultsPage;

/**
 * Class TimeitClient
 * @package Sportic\Omniresult\Timeit
 */
class TimeitClient extends TimingClient
{
    use HasDetectorTrait;

//    /**
//     * @param $parameters
//     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\ResultsPage
//     */
//    public function results($parameters)
//    {
//        return $this->executeScrapper(ResultsPage::class, $parameters);
//    }
//
//    /**
//     * @param $parameters
//     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\ResultPage
//     */
//    public function result($parameters)
//    {
//        return $this->executeScrapper(ResultPage::class, $parameters);
//    }
}
