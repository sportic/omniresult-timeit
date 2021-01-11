<?php

namespace Sportic\Omniresult\Timeit;

use Sportic\Omniresult\Common\RequestDetector\Detectors\AbstractUrlDetector;

/**
 * Class RequestDetector
 * @package Sportic\Omniresult\Trackmyrace
 */
class RequestDetector extends AbstractUrlDetector
{
    protected $pathParts = null;

    /**
     * @inheritdoc
     */
    protected function isValidRequest()
    {
        $host = $this->getUrlComponent('host');
        if (!in_array($host, ['www.time-it.ro', 'time-it.ro', 'time-it.go.ro'])) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    protected function detectAction(): string
    {
        $pathParts = $this->getPathParts();

        $year = $pathParts[0];
        if ($year < 2014 || $year > date('Y') + 1) {
            return '';
        }

        $query = $this->getUrlComponent('query');

        if (empty($query)) {
            return 'event';
        }
        parse_str($query, $params);
        if (isset($params['sort'])) {
            return 'results';
        }
        return 'event';
    }

    /**
     * @inheritdoc
     */
    protected function detectParams()
    {
        $return = [];

        $return['host'] = $this->getUrlComponent('host');

        $pathParts = $this->getPathParts();
        $return['eventSlug'] = $pathParts[0].'/'.$pathParts[1];

        $query = $this->getUrlComponent('query');

        if (empty($query)) {
            return $return;
        }
        parse_str($query, $params);
        if (isset($params['sort'])) {
            $return['slug'] = $params['sort'];
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getPathParts(): array
    {
        if ($this->pathParts === null) {
            $this->detectUrlPathParts();
        }
        return $this->pathParts;
    }

    protected function detectUrlPathParts()
    {
        $path = trim($this->getUrlComponent('path'),'/');
        $parts = explode('/', $path);
        $this->pathParts = $parts;
    }
}
