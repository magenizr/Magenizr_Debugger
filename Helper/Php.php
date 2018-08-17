<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

namespace Magenizr\Debugger\Helper;

/**
 * Class FileSystem
 * @package Magenizr\Debugger\Helper
 */
class Php extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @return string
     */
    public function getPhpVersion()
    {
        return phpversion();
    }
}
