<?php

namespace Sportic\Omniresult\Timeit\Parsers;

use DOMElement;
use Nip\Utility\Str;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ResultsPage
 * @package Sportic\Omniresult\Timeit\Parsers
 */
class ResultsPage extends AbstractParser
{
    protected $returnContent = [];

    protected const LEG_START_STRING = 'leg-';

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
            $fieldNameOriginal = trim(strip_tags($cell->nodeValue));
            $fieldName = strtolower($fieldNameOriginal);
            $labelFind = $fieldMap[$fieldName] ?? null;
            if ($labelFind) {
                $return[$colNum] = $labelFind;
            } elseif (Str::startsWith($fieldNameOriginal, 'Timp ')) {
                $return[$colNum] = self::LEG_START_STRING . Str::replace('Timp ','', $fieldNameOriginal);
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
            $this->parseResultsRowCell($cell, $headerMaps, $colNum, $parameters);
        }

        if (count($parameters) < 1) {
            return false;
        }
        if ($parameters['fullNameLF'] == '~') {
            return false;
        }
        $parameters['category'] = $this->getScraper()->getParameter('categoryName');
        return new Result($parameters);
    }
    protected function parseResultsRowCell(DOMElement $cell, $headerMaps, &$colNum, &$parameters = [])
    {
        if (!($cell instanceof DOMElement)) {
            return;
        }

        $field = $headerMaps[$colNum] ?? null;
        $colNum++;
        if ($field) {
            $this->parseResultsRowField($cell, $field, $parameters);
            return;
        }
    }
    /**
     * @param DOMElement $cell
     * @param $field
     * @param array $parameters
     */
    protected function parseResultsRowField(DOMElement $cell, $field, &$parameters = [])
    {
        if (Str::startsWith($field, self::LEG_START_STRING)) {
            $field = Str::replace(self::LEG_START_STRING, '', $field);
            $this->parseResultsRowFieldLeg($cell, $field, $parameters);
            return;
        }
        $method = 'parseRowCell' . ucfirst($field);
        if (method_exists($this, $method)) {
            $value = $this->$method($cell, $parameters);
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
     * @param array $parameters
     * @return string|void
     */
    protected function parseRowCellGender(DOMElement $cell, &$parameters = []): string
    {
        $image = $cell->getElementsByTagName('img');
        if ($image->length < 1) {
            return '';
        }
        $image = $image->item(0);
        $src = $image->getAttribute('src');
        if (strpos($src, 'diploma.png') !== false) {
            $this->parseResultsRowField($cell, 'posGender', $parameters);
            return '';
        }
        if (strpos($src, 'm.png') !== false) {
            return 'male';
        }

        if (strpos($src, 'f.png') !== false) {
            return 'female';
        }

        return '';
    }

    protected function parseResultsRowFieldLeg(DOMElement $cell, string $field, array &$parameters)
    {
        $split = new Split();
        $split->setTime($field);
        $value = trim($cell->nodeValue);
        $split->setParameters(['time' => $value]);
        $parameters['splits'][] = $split;
    }
    /**
     * @return array
     */
    protected function parseResultsPagination()
    {
        $categories = $this->getParameter('categories', []);
        return [
            'current' => $this->getParameter('page', 1),
            'all' => count($categories),
        ];
    }

    /**
     * @return array
     */
    public static function getLabelMaps(): array
    {
        return [
            'bib' => 'bib',
            'nume' => 'fullNameLF',
            'sex' => 'gender',
            'gender' => 'gender',
            'category' => 'agegroup',
            'loc categorie' => 'posCategory',
            'categorie' => 'posCategory',
            'loc open sex' => 'posGender',
            'loc general' => 'posGen',
            'general' => 'posGen',
            'echipa' => 'team_name',
            'sosire' => 'time',
            'finish' => 'time',
            'timp' => 'time',
            'timp sosire' => 'time',
            'timp finish' => 'time',
        ];
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName(): string
    {
        return ListContent::class;
    }

    /**
     * @inheritdoc
     */
    public function getModelClassName(): string
    {
        return Result::class;
    }

}
