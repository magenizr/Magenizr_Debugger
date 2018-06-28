<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     https://www.magenizr.com/license/ Magenizr EULA
 */

namespace Magenizr\Debugger\Block\Adminhtml;

/**
 * Class Base
 * @package Magenizr\Debugger\Block\Adminhtml
 */
class Base extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magenizr\Debugger\Helper\FileSystem
     */
    protected $fileSystem;

    /**
     * @var \Magenizr\Debugger\Helper\Php
     */
    protected $php;

    /**
     * Dashboard constructor.
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
        parent::__construct($context);

        $this->helper = $helper;
        $this->php = $php;
        $this->fileSystem = $fileSystem;
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
