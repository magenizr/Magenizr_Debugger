<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

namespace Magenizr\Debugger\Block\Adminhtml;

/**
 * Class Base
 * @package Magenizr\Debugger\Block\Adminhtml
 */
class Base extends \Magento\Framework\View\Element\Template
{
    /**
     * Base constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenizr\Debugger\Helper\Data $helper
     * @param \Magenizr\Debugger\Helper\Php $php
     * @param \Magenizr\Debugger\Helper\FileSystem $fileSystem
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenizr\Debugger\Helper\Data $helper,
        \Magenizr\Debugger\Helper\Php $php,
        \Magenizr\Debugger\Helper\FileSystem $fileSystem
    ) {
        $this->helper = $helper;
        $this->php = $php;
        $this->fileSystem = $fileSystem;

        parent::__construct($context);
    }

    /**
     * @return \Magenizr\Debugger\Helper\FileSystem
     */
    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * @return \Magenizr\Debugger\Helper\Php
     */
    public function getPhp()
    {
        return $this->php;
    }

    /**
     * @return \Magenizr\Debugger\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
