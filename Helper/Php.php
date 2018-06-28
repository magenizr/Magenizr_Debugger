<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     https://www.magenizr.com/license/ Magenizr EULA
 */

namespace Magenizr\Debugger\Helper;

/**
 * Class FileSystem
 * @package Magenizr\Debugger\Helper
 */
class Php extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $phpInfoArray;

    /**
     * FileSystem constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {

        $this->phpInfoArray = $this->getPhpInfoArray();

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getPhpVersion()
    {
        $phpinfo = $this->getPhpInfoArray();

        return $phpinfo['Core']['PHP Version'];
    }

    /**
     * @return mixed
     */
    public function getCore() {
        return $this->phpInfoArray['Core'];
    }

    /**
     * @return mixed
     */
    public function getGeneral() {
        return $this->phpInfoArray['General'];
    }

    public function getPhpInfoExcept($except = []) {

        // ToDo: Remove all non-arrays
        // ToDo: Remove $except from phpInfoArray
//        foreach ($this->phpInfoArray as $value) {
////
////            var_dump($value);
////            die();
//        }

        return $this->phpInfoArray['General'];
    }

    public function getPhpVariables() {
        return $this->phpInfoArray['PHP Variables'];
    }

    /**
     * @return array
     */
    public function getPhpInfoArray()
    {
        ob_start();
        phpinfo();

        $info_arr = array();
        $info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));

        $category = 'General';

        foreach($info_lines as $line) {

            preg_match("~<h2>(.*)</h2>~", $line, $title) ? $category = $title[1] : null;
            if (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
                $info_arr[$category][trim($val[1])] = $val[2];
            } elseif (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
                $info_arr[$category][trim($val[1])] = array("local" => $val[2], "master" => $val[3]);
            }
        }

        return $info_arr;
    }

}
