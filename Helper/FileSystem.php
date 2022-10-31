<?php
declare(strict_types=1);
/**
 * Magenizr Debugger
 *
 * @copyright   Copyright (c) 2018 - 2022 Magenizr (https://www.magenizr.com)
 * @license     https://www.magenizr.com/license Magenizr EULA
 */

namespace Magenizr\Debugger\Helper;

class FileSystem extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $driverFile;

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $fileSystemIo;

    /**
     * @var Data
     */
    private $data;

    /**
     * @var \Magento\Framework\Archive\Tar
     */
    private $tarArchive;

    /**
     * @var string
     */
    private $lastReportFile;

    /**
     * Init Constructor
     *
     * @param \Magento\Framework\Filesystem\Driver\File $driverFile
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Io\File $fileSystemIo
     * @param Data $data
     * @param \Magento\Framework\Archive\Tar $tarArchive
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Io\File $fileSystemIo,
        \Magenizr\Debugger\Helper\Data $data,
        \Magento\Framework\Archive\Tar $tarArchive,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->driverFile = $driverFile;
        $this->directoryList = $directoryList;
        $this->fileSystemIo = $fileSystemIo;
        $this->data = $data;
        $this->tarArchive = $tarArchive;

        parent::__construct($context);
    }

    /**
     * Return driverFile
     *
     * @return \Magento\Framework\Filesystem\Driver\File
     */
    public function getDriverFile()
    {
        return $this->driverFile;
    }

    /**
     * Return fileSystemIo
     *
     * @param string $filePath
     * @return mixed
     */
    public function getFileSystemIo()
    {
        return $this->fileSystemIo;
    }

    /**
     * Return directoryList
     *
     * @return \Magento\Framework\Filesystem\DirectoryList
     */
    public function getDir()
    {
        return $this->directoryList;
    }

    /**
     * Return Absolute Path
     *
     * @param string $dir
     * @return mixed
     */
    public function getAbsolutePath($dir = '')
    {
        $path = $this->getDriverFile()->getAbsolutePath(
            $this->getDir()->getRoot() . DIRECTORY_SEPARATOR,
            $dir
        );

        return ($this->getDriverFile()->isDirectory($path)) ? $path : false;
    }

    /**
     * Return folder size
     *
     * @param string $path
     * @return int
     */
    public function getDirSize($path)
    {
        $size = 0;
        $files = $this->getDriverFile()->readDirectoryRecursively($path);

        foreach ($files as $file) {
            $stat = $this->getDriverFile()->stat($file);

            $this->getDriverFile()->isFile($file) && $size += $stat['size'];
        }

        return $size;
    }

    /**
     * Return folder details
     *
     * @param string $type
     * @return array
     */
    public function getDirDetails($type)
    {
        $details = [];
        $dir = $this->data->getPathByType($type);
        $path = $this->getAbsolutePath($dir);

        if ($path) {
            $size = $this->getDirSize($path);

            if ($size == 0) {
                return $details;
            }

            $details['path'] = $path;
            $details['size'] = $this->getHumanFilesize($size);
            $details['name'] = $this->getLabel($dir);
        }

        return $details;
    }

    /**
     * Return label
     *
     * @param string $dir
     * @return array
     */
    public function getLabel($dir)
    {
        $labels = [
            'var/log' => __('Logs'),
            'var/report' => __('Reports'),
        ];

        return $labels[$dir];
    }

    /**
     * Return files from directory by path
     *
     * @param string $path
     * @return array
     */
    public function getFilesFromDirectory($path)
    {
        if ($this->getDriverFile()->isDirectory($path)) {
            $list = [];

            $files = $this->getDriverFile()->readDirectory($path);

            foreach ($files as $file) {
                $stat = $this->getDriverFile()->stat($file);

                $list[$stat['ctime']] = [
                    'name' => $file,
                    'realpath' => $this->getDriverFile()->getRealPath($file)
                ];
            }

            return $list;
        }

        return [];
    }

    /**
     * Set last report file
     *
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function setLastReportFile()
    {
        if ($path = $this->getAbsolutePath('var/report')) {
            // List of files
            $list = $this->getFilesFromDirectory($path);

            if (!empty($list)) {
                sort($list);

                $file = array_values($list)[0];

                if ($this->getDriverFile()->fileGetContents($file['realpath'])) {
                    $this->lastReportFile = $file['realpath'];
                }
            }
        }
    }

    /**
     * Return last report file
     *
     * @return false|string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getLastReportFile()
    {
        $this->setLastReportFile();

        if ($this->lastReportFile && $this->getDriverFile()->fileGetContents($this->lastReportFile)) {
            return $this->lastReportFile;
        }

        return false;
    }

    /**
     * Return content of most recent report file
     *
     * @return bool
     */
    public function getLastReportFileContent()
    {
        if ($file = $this->getLastReportFile()) {
            return $this->getDriverFile()->fileGetContents($file);
        }

        return false;
    }

    /**
     * Tar folder
     *
     * @param string $sourceDir
     * @param string $destFile
     * @return mixed
     */
    public function packFolder($sourceDir, $destFile)
    {
        if ($this->getDriverFile()->isDirectory($sourceDir)) {
            return $this->tarArchive->pack($sourceDir, $destFile);
        }
    }

    /**
     * Return readable file size
     *
     * @param string $bytes
     * @return string
     */
    public function getHumanFilesize($bytes = 0)
    {
        $base = log($bytes) / log(1024);
        $suffix = ['B', 'KB', 'MB', 'GB', 'TB'];
        $fbase = floor($base);

        return ($bytes == 0) ? $bytes : round(pow(1024, $base - floor($base)), 1) . $suffix[$fbase];
    }
}
