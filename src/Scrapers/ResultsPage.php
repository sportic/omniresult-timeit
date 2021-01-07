<?php

namespace Sportic\Omniresult\Timeit\Scrapers;

use Sportic\Omniresult\Timeit\Parsers\EventPage as Parser;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\Timeit\Scrapers
 *
 * @method Parser execute()
 */
class ResultsPage extends AbstractScraper
{
    /**
     * @return mixed
     */
    public function getEventSlug()
    {
        return $this->getParameter('eventSlug');
    }

    /**
     * @return mixed
     */
    public function getRaceSlug()
    {
        return $this->getParameter('raceSlug');
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->getParameter('page', 1);
    }

    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        $client = $this->getClient();
        $crawler = $client->request(
            'GET',
            $this->getCrawlerUri()
        );

        return $crawler;
    }

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost()
            . '/en/running/event-zone/ajax/event/'
            . $this->getEventSlug()
            . '/resultstable/'
            . $this->getRaceSlug()
            . '/expanded/page/'
            . ($this->getPage() - 1)
            . '/';
    }
}
