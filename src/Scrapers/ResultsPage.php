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
    public function getCategorySlug()
    {
        return $this->getParameter('categorySlug');
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
    public function getCrawlerUri(): string
    {
        return $this->getCrawlerUriHost()
            . '/' . $this->getEventSlug()
            . '/?slug='            . $this->getCategorySlug()
            . '&head=off';
    }
}
