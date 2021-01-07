<?php

namespace Sportic\Omniresult\Timeit;

/**
 * Class Helper
 * @package Sportic\Omniresult\Timeit
 */
class Helper extends \Sportic\Omniresult\Common\Helper
{

    /**
     * @return array
     */
    public static function getLanguages()
    {
        return ['de', 'fr', 'it', 'en', 'ro'];
    }

    /**
     * @return array
     */
    public static function getRegions()
    {
        return ['europe', 'germany', 'france', 'romania'];
    }
}
