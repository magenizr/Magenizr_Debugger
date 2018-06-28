<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     https://www.magenizr.com/license/ Magenizr EULA
 */

namespace Magenizr\Debugger\Controller\Adminhtml\Download;

use Magento\Catalog\Model;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class File
 * @package Magenizr\Debugger\Controller\Adminhtml\Download
 */
class File extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $dir;

    /**
     * File constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magenizr\Debugger\Helper\Data $helper
     * @param \Magenizr\Debugger\Helper\FileSystem $fileSystem
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magenizr\Debugger\Helper\Data $helper,
        \Magenizr\Debugger\Helper\FileSystem $fileSystem,
        \Magento\Framework\Filesystem\DirectoryList $dir
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->fileFactory = $fileFactory;
        $this->messageManager = $messageManager;
        $this->helper = $helper;
        $this->filesystem = $fileSystem;
        $this->dir = $dir;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $time = time();
        $type = $this->getRequest()->getParam('type');
        $dir = $this->getRequest()->getParam('dir');
        $sourceDir = $this->filesystem->getPath($dir);

        // Temporary storage folder
        $destDir = sys_get_temp_dir();

        $fileName = sprintf('%s_%s.zip', $type, $time);
        $destFile = sprintf('%s%s', $destDir, $fileName);

        try {

            if (is_readable($sourceDir)) {

            }

            // Zip folder
            $this->filesystem->addDirToZip($sourceDir, $destFile);

            // Stream file to browser
            if (is_readable($destFile)) {

                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                header('Content-Length: '.filesize($destFile) );
                readfile($destFile);
            }

        } catch (\Exception $e) {
            $this->messageManager->addException($e, $e->getMessage());
        }
    }

}
