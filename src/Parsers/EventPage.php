<?php

namespace Sportic\Omniresult\Timeit\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\Common\Models\RaceCategory;
use Sportic\Omniresult\Common\Models\Result;

/**
 * Class EventPage
 * @package Sportic\Omniresult\Timeit\Parsers
 */
class EventPage extends AbstractParser
{
    protected $returnContent = [];
    protected $sorts = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $this->returnContent['records'] = $this->parseRaces();
        return $this->returnContent;
    }

    /**
     * @return array
     */
    protected function parseRaces()
    {
        $return = [];
        $links = $this->getCrawler()->filter('a');
        $sorts = [];
        foreach ($links as $link) {
            $sort = $this->parseLink($link);
            if (is_array($sort)) {
                $sorts = array_merge($sorts, $sort);
            }
        }
        $categories = $this->classifySorts($sorts);
        foreach ($categories as $catSlug => $category) {
            $parameters = [
                'name' => $category['name'],
                'race' => $category['race'],
                'id' => $catSlug
            ];
            $parameters['id'] = $catSlug;
            $return[] = new RaceCategory($parameters);
        }

        return $return;
    }

    /**
     * @param DOMElement $link
     */
    protected function parseLink(DOMElement $link)
    {
        $url = $link->getAttribute('href');
        $query = parse_url($url,PHP_URL_QUERY);
        if (empty($query)) {
            return;
        }
        parse_str($query, $queryParams);

        if (!isset($queryParams['sort']) || empty($queryParams['sort'])) {
            return;
        }
        $classes = array_filter(explode(' ', $link->getAttribute('class')));
        $name = trim(strip_tags( $link->nodeValue));
        $sortParam = $queryParams['sort'];
        return [$sortParam => $name];
    }

    protected function classifySorts(array $sorts): array
    {
        $categories = [];
        $races = [];
        foreach ($sorts as $slug => $name) {
            $categories[$slug] = [
                'name' => $name,
                'race' => '',
            ];
            $raceSlug = $slug;
            while (strlen($raceSlug) > 1) {
                $raceSlug = substr($raceSlug, 0, -1);
                if (isset($sorts[$raceSlug])) {
                    $categories[$slug]['race'] = $sorts[$raceSlug];
                    $races[$raceSlug] = $sorts[$raceSlug];
                }
            }
        }
        foreach ($races as $raceSlug => $race) {
            unset($categories[$raceSlug]);
        }
        return $categories;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return ListContent::class;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return RaceCategory::class;
    }
}
