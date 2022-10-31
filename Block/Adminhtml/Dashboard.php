<?php
declare(strict_types=1);
/**
 * Magenizr Debugger
 *
 * @copyright   Copyright (c) 2018 - 2022 Magenizr (https://www.magenizr.com)
 * @license     https://www.magenizr.com/license Magenizr EULA
 */

namespace Magenizr\Debugger\Block\Adminhtml;

class Dashboard extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magenizr\Debugger\Helper\Data
     */
    private $helper;

    /**
     * @var \Magenizr\Debugger\Helper\Php
     */
    private $php;

    /**
     * @var \Magenizr\Debugger\Helper\FileSystem
     */
    private $fileSystem;

    /**
     * Init Constructor
     *
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
     * Return fileSystem
     *
     * @return \Magenizr\Debugger\Helper\FileSystem
     */
    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * Return php
     *
     * @return \Magenizr\Debugger\Helper\Php
     */
    public function getPhp()
    {
        return $this->php;
    }

    /**
     * Return create time of a file
     *
     * @param string $file
     * @return false|string
     */
    public function getFileCTime($file)
    {
        $file = $this->getFileSystem()->getDriverFile()->stat($file);

        return date('F d Y H:i:s', $file['ctime']);
    }

    /**
     * Return basename of a file
     *
     * @param string $file
     * @return mixed
     */
    public function getBasename($file)
    {
        $file = $this->getFileSystem()->getFileSystemIo()->getPathInfo($file);

        return $file['basename'];
    }

    /**
     * Return helper
     *
     * @return \Magenizr\Debugger\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
