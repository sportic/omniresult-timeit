<?php

namespace Sportic\Omniresult\Timeit\Scrapers;

use ByTIC\GouttePhantomJs\Clients\ClientFactory;
use Goutte\Client;

/**
 * Class AbstractScraper
 * @package Sportic\Omniresult\Timeit\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Omniresult\Common\Scrapers\AbstractScraper
{
    /** @noinspection PhpMissingParentCallCommonInspection
     * @return Client
     */
    protected function generateClient()
    {
        return ClientFactory::getGoutteClient();
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
        return 'https://'.$this->getParameter('host','time-it.ro');;
    }
}
