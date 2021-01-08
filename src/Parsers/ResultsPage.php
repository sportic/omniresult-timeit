<?php

namespace Sportic\Omniresult\Timeit\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Result;
use Symfony\Component\DomCrawler\Crawler;

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
    protected function generateContent(): array
    {
        $this->returnContent['records'] = $this->parseResults();
        $this->returnContent['pagination'] = $this->parseResultsPagination();

        return $this->returnContent;
    }

    /**
     * @return array
     */
    protected function parseResults()
    {
        $table = $this->getCrawler()->filter('table.container')->last();
        /** @var Crawler $resultsRows */
        $resultsRows = $table->filter('tr');
        if ($resultsRows->count() < 1) {
            return [];
        }
        return $this->parseResultsTable($resultsRows);
    }

    protected function parseResultsTable(Crawler $resultsRows): array
    {
        $results = [];
        foreach ($resultsRows as $i => $resultRow) {
            if ($i == 0) {
                $headerMaps = $this->parseResultsHeader($resultRow);
                continue;
            }
            $result = $this->parseResultsRow($resultRow, $headerMaps);
            if ($result) {
                $results[] = $result;
            }
        }
        return $results;
    }

    /**
     * @return array
     */
    protected function parseResultsHeader(DOMElement $headerRow)
    {
        $columns = $headerRow->childNodes;
        $fieldMap = self::getLabelMaps();

        if ($columns->count() < 1) {
            return [];
        }
        $return = [];
        $colNum = 0;
        foreach ($columns as $cell) {
            if (!($cell instanceof DOMElement)) {
                continue;
            }
            $fieldName = strtolower(trim(strip_tags($cell->nodeValue)));
            $labelFind = isset($fieldMap[$fieldName]) ? $fieldMap[$fieldName] : null;
            if ($labelFind) {
                $return[$colNum] = $labelFind;
            }
            $colNum++;
        }

        return $return;
    }

    /**
     * @param DOMElement $row
     *
     * @return bool|Result
     */
    protected function parseResultsRow(DOMElement $row, array $headerMaps)
    {
        $parameters = [];

        $colNum = 0;
        foreach ($row->childNodes as $cell) {
            if (!($cell instanceof DOMElement)) {
                continue;
            }

            $field = isset($headerMaps[$colNum]) ? $headerMaps[$colNum] : null;
            $colNum++;
            if (!$field) {
                continue;
            }
            $this->parseResultsRowCell($cell, $field, $parameters);
        }

        if (count($parameters)) {
            return new Result($parameters);
        }

        return false;
    }

    /**
     * @param DOMElement $cell
     * @param $field
     * @param array $parameters
     */
    protected function parseResultsRowCell(DOMElement $cell, $field, &$parameters = [])
    {
        if ($field == 'gender') {
            $value = $this->parseGender($cell);
            if ($value) {
                $parameters[$field] = $value;
            }
            return;
        }

        $value = trim($cell->nodeValue);
        $parameters[$field] = $value;
    }

    /**
     * @param DOMElement $cell
     * @return string|void
     */
    protected function parseGender(DOMElement $cell): string
    {
        $image = $cell->getElementsByTagName('img');
        if ($image->length < 1) {
            return '';
        }
        $image = $image->item(0);
        $src = $image->getAttribute('src');
        if (strpos($src, 'm.png') !== false) {
            return 'male';
        }

        if (strpos($src, 'f.png') !== false) {
            return 'female';
        }

        return '';
    }


    /**
     * @return array
     */
    protected function parseResultsPagination()
    {
        return [
            'current' => 1,
            'all' => 1,
            'items' => 1,
        ];
    }

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'bib' => 'bib',
            'nume' => 'fullName',
            'sex' => 'gender',
            'gender' => 'gender',
            'category' => 'agegroup',
            'loc categorie' => 'posCategory',
            'loc open sex' => 'posGender',
            'loc general' => 'posGen',
            'echipa' => 'team_name',
            'timp' => 'time',
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
