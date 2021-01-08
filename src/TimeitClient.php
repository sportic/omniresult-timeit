<?php

namespace Sportic\Omniresult\Timeit;

use Sportic\Omniresult\Common\RequestDetector\HasDetectorTrait;
use Sportic\Omniresult\Common\TimingClient;
use Sportic\Omniresult\Timeit\Scrapers\EventPage;
use Sportic\Omniresult\Timeit\Scrapers\ResultsPage;

/**
 * Class TimeitClient
 * @package Sportic\Omniresult\Timeit
 */
class TimeitClient extends TimingClient
{
    use HasDetectorTrait;

    /**
     * @param $parameters
     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\EventPage
     */
    public function event($parameters)
    {
        return $this->executeScrapper(EventPage::class, $parameters);
    }

    /**
     * @param $parameters
     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\ResultsPage
     */
    public function results($parameters)
    {
        return $this->executeScrapper(ResultsPage::class, $parameters);
    }
}
