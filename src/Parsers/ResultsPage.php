<?php

namespace Sportic\Omniresult\Timeit\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;
use Sportic\Omniresult\Common\Models\SplitCollection;
use Sportic\Omniresult\Timeit\Helper;

/**
 * Class ResultsPage
 * @package Sportic\Omniresult\Timeit\Parsers
 */
class ResultsPage extends AbstractParser
{
    protected $returnContent = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $this->returnContent['records'] = $this->parseResultsTable();
        $this->returnContent['pagination'] = $this->parseResultsPagination();

        return $this->returnContent;
    }

    /**
     * @return array
     */
    protected function parseResultsTable()
    {
        $return = [];
        $resultsRows = $this->getCrawler()->filter(
            'tr.content_row'
        );
        if ($resultsRows->count() > 0) {
            foreach ($resultsRows as $resultRow) {
                $result = $this->parseResultsRow($resultRow);
                if ($result) {
                    $return[] = $result;
                }
            }
        }

        return $return;
    }

    /**
     * @return array
     */
    protected function parseResultsHeader()
    {
        $return = [];

        $fields = $this->getCrawler()->filter(
            '#ctl00_Content_Main_grdNew_DXHeadersRow table td a'
        );
        $fieldMap = self::getLabelMaps();
        if ($fields->count() > 0) {
            $colNum = 0;
            foreach ($fields as $field) {
                $fieldName = $field->nodeValue;
                $labelFind = array_search($fieldName, $fieldMap);
                if ($labelFind) {
                    $return[$colNum] = $labelFind;
                }
                $colNum++;
            }
        }

        return $return;
    }

    /**
     * @param DOMElement $row
     *
     * @return bool|Result
     */
    protected function parseResultsRow(DOMElement $row)
    {
        $parameters = [];
        foreach ($row->childNodes as $cell) {
            if ($cell instanceof DOMElement) {
                $parameters = $this->parseResultsRowCell($cell, $parameters);
            }
        }
        $parameters['id'] = $this->parseResultId($row);

        if (count($parameters)) {
            return new Result($parameters);
        }

        return false;
    }

    /**
     * @param DOMElement $cell
     * @param array $parameters
     *
     * @return array
     */
    protected function  parseResultsRowCell(DOMElement $cell, $parameters = [])
    {
        $class = $cell->getAttribute('class');
        $type = trim(str_replace(['first', 'last', 'odd', 'even'], '', $class));

        if (strpos($type, ' round')) {
            $this->parseRound($type, $cell, $parameters);
            return $parameters;
        }

        $field = array_search($type, self::getLabelMaps());

        if ($field) {
            $parameters[$field] = trim($cell->nodeValue);
            return $parameters;
        }

        if (!$this->parseCountry($cell, $parameters)) {
            $this->parseResultName($cell, $parameters);
        }

        return $parameters;
    }

    /**
     * @param DOMElement $row
     * @return string
     */
    protected function parseResultId(DOMElement $row)
    {
        $resultUrl = $row->getAttribute('rel');
        $resultId = str_replace(
            ['en/event-zone/ajax/event/'],
            '',
            $resultUrl);
        return $resultId;
    }

    /**
     * @param DOMElement $cell
     * @param $parameters
     * @return bool
     */
    protected function parseResultName($cell, &$parameters)
    {
        $spans = $cell->getElementsByTagName('span');
        if ($spans->length > 0) {
            $firstSpan = $spans->item(0);
            $class = $firstSpan->getAttribute('class');
            $name = $firstSpan->getAttribute('title');
            if ($class == 'tip') {
                $parameters['fullName'] = $name;
            }
            return true;
        }
        return false;
    }

    /**
     * @param string $round
     * @param DOMElement $cell
     * @param $parameters
     * @return bool
     */
    protected function parseRound($round, $cell, &$parameters)
    {
        $roundName = trim(str_replace(' round', '', $round));
        $parameters['splits'] = isset($parameters['splits']) ? $parameters['splits'] : new SplitCollection();

        $parameters['splits'][] = new Split(['name' => $roundName, 'time' => trim($cell->nodeValue)]);
        return false;
    }

    /**
     * @param DOMElement $cell
     * @param $parameters
     * @return bool
     */
    protected function parseCountry($cell, &$parameters)
    {
        $imageCountry = $cell->getElementsByTagName('img');
        if ($imageCountry->length > 0) {
            $image = $imageCountry->item(0);
            $src = $image->getAttribute('src');
            if (strpos($src, 'flags')) {
                $parameters['country'] = $image->getAttribute('title');
            }
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    protected function parseResultsPagination()
    {
        $return = [
            'current' => 1,
            'all' => 1,
            'items' => 1,
        ];

        $paginationObject = $this->getCrawler()->filter(
            '.tmr_event_pagination_content'
        );

        if ($paginationObject->count() > 0) {
            $return['current'] = $paginationObject->filter('a.page-current')->text();

            $lastURL = $paginationObject->filter('a.page-last')->getNode(0)->getAttribute('href');
            $lastPage = substr($lastURL, strpos($lastURL, '/expanded/page/') + 15, -1);
            $return['all'] = $lastPage + 1;
        }

        $countElement = $this->getCrawler()->filter('#participantCount');
        if ($countElement->count() > 0) {
            $return['items'] = intval($countElement->text());
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'posGen' => 'total_place',
            'bib' => 'start_nr',
            'gender' => 'gender',
            'category' => 'agegroup',
            'posCategory' => 'agegroup_place',
            'posGender' => 'gender_place',
            'club' => 'club',
            'time' => 'brutto',
        ];
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
        return Result::class;
    }
}
