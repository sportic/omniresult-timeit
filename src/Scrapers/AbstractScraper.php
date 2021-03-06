<?php

namespace Sportic\Omniresult\Timeit\Scrapers;

use ByTIC\GouttePhantomJs\Clients\ClientFactory;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class AbstractScraper
 * @package Sportic\Omniresult\Timeit\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Omniresult\Common\Scrapers\AbstractScraper
{
    /** @noinspection PhpMissingParentCallCommonInspection
     * @return Client
     */
    protected function generateClient(): Client
    {
        $client = HttpClient::create(
            [
                'verify_peer' => false,
            ]
        );

        return ClientFactory::getGoutteClient($client);
    }

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
