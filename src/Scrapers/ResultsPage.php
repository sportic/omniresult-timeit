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
    public function getCategories()
    {
        return array_filter($this->getParameter('categories', []));
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        $page = $this->getParameter('page', 1);
        return $page < 1 ? 1 : $page;
    }

    /**
     * @return mixed
     */
    public function getCategorySlug()
    {
        $slug = $this->getParameter('categorySlug');
        if (empty($slug)) {
            $categories = $this->getCategories();
            $page = $this->getPage() - 1;
            $keys = array_keys($categories);
            if (isset($keys[$page])) {
                $slug = $keys[$page];
                $this->setParameter('categorySlug', $slug);
                $this->setParameter('categoryName', $categories[$slug]);
            }
        }
        return $slug;
    }

    /**
     * @inheritdoc
     */
    protected function generateParserData(): array
    {
        $data = parent::generateParserData();

        $data['page'] = $this->getPage();
        $data['categories'] = $this->getCategories();
        return $data;
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
            . '/?sort=' . $this->getCategorySlug()
            . '&head=off';
    }
}
