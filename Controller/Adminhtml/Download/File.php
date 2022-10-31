<?php
declare(strict_types=1);
/**
 * Magenizr Debugger
 *
 * @copyright   Copyright (c) 2018 - 2022 Magenizr (https://www.magenizr.com)
 * @license     https://www.magenizr.com/license Magenizr EULA
 */

namespace Magenizr\Debugger\Controller\Adminhtml\Download;

use Magento\Catalog\Model;
use Magento\Framework\Exception\CouldNotDeleteException;

class File extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Magenizr_Debugger::debugger_dashboard';

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    private $resultRawFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    private $fileFactory;

    /**
     * @var \Magenizr\Debugger\Helper\Data
     */
    private $helper;

    /**
     * @var \Magenizr\Debugger\Helper\FileSystem
     */
    private $fileSystem;

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * Init Constructor
     *
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
     * Init execute
     *
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
     * Return filesystem
     *
     * @return \Magenizr\Debugger\Helper\FileSystem
     */
    private function getFileSystem()
    {
        return $this->fileSystem;
    }
}
