<?php
declare(strict_types=1);
/**
 * Magenizr Debugger
 *
 * @copyright   Copyright (c) 2018 - 2022 Magenizr (https://www.magenizr.com)
 * @license     https://www.magenizr.com/license Magenizr EULA
 */

namespace Magenizr\Debugger\Helper;

class Php extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Return php version
     *
     * @return string
     */
    public function getPhpVersion()
    {
        return phpversion();
    }
}
