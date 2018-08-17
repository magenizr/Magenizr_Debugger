<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
     * File constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magenizr\Debugger\Helper\Data $helper
     * @param \Magenizr\Debugger\Helper\FileSystem $fileSystem
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magenizr\Debugger\Helper\Data $helper,
        \Magenizr\Debugger\Helper\FileSystem $fileSystem,
        \Magento\Framework\Filesystem\DirectoryList $directoryList
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->fileFactory = $fileFactory;
        $this->helper = $helper;
        $this->fileSystem = $fileSystem;
        $this->directoryList = $directoryList;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $time = time();
        $type = $this->getRequest()->getParam('type');
        $dir = $this->helper->getPathByType($type);
        $sourceDir = $this->getFileSystem()->getAbsolutePath($dir);

        // Temporary storage folder
        $destDir = $this->directoryList->getPath('var');

        $fileName = sprintf('%s_%s.tar', $type, $time);
        $destFile = sprintf('%s/%s', $destDir, $fileName);

        try {
            if ($this->getFileSystem()->getDriverFile()->isWritable($destDir)) {
                // Zip folder
                $destFile = $this->getFileSystem()->packFolder($sourceDir, $destFile);

                // Stream file to browser
                $this->fileFactory->create(
                    $fileName,
                    $this->getFileSystem()->getDriverFile()->fileGetContents($destFile),
                    \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
                    'application/octet-stream'
                );

                $resultRaw = $this->resultRawFactory->create();

                // Delete zipped file
                 $this->getFileSystem()->getDriverFile()->deleteFile($destFile);

                return $resultRaw;
            }
        } catch (\Exception $e) {
            $this->messageManager->addException($e, $e->getMessage());
        }
    }

    /**
     * @return \Magenizr\Debugger\Helper\FileSystem
     */
    private function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenizr_Debugger::debugger_dashboard');
    }
}
