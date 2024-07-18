<?php

namespace Sportic\Omniresult\Timeit\Scrapers;

/**
 * Class AbstractScraper
 * @package Sportic\Omniresult\Timeit\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Omniresult\Common\Scrapers\AbstractScraper
{


    /**
     * @return array
     */
    protected function generateParserData(): array
    {
        $this->getRequest();

        return [
            'scraper' => $this,
            'crawler' => $this->getCrawler(),
            'response' => $this->getClient()->getResponse(),
        ];
    }

    /**
     * @return string
     */
    abstract public function getCrawlerUri();

    /**
     * @return string
     */
    protected function getCrawlerUriHost()
    {
        $host = $this->getParameter('host', 'time-it.ro');
        $host = empty($host) ? 'time-it.ro' : $host;
        return 'https://' . $host;
    }
}
