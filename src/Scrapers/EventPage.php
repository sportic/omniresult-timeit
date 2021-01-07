<?php

namespace Sportic\Omniresult\Timeit\Scrapers;

use Sportic\Omniresult\Timeit\Parsers\EventPage as Parser;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\Timeit\Scrapers
 *
 * @method Parser execute()
 */
class EventPage extends AbstractScraper
{
    /**
     * @return mixed
     */
    public function getEventSlug()
    {
        return $this->getParameter('eventSlug');
    }

    /**
     * @throws \Sportic\Omniresult\Common\Exception\InvalidRequestException
     */
    protected function doCallValidation()
    {
        parent::doCallValidation();
        $this->validate('eventSlug');
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
            . '/' . $this->getEventSlug()
            . '/';
    }
}
