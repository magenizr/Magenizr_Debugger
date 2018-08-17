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
class FileSystem extends \Magento\Framework\App\Helper\AbstractHelper
{

    private $lastReportFile;

    /**
     * FileSystem constructor.
     * @param \Magento\Framework\Filesystem\Driver\File $driverFile
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param Data $data
     * @param \Magento\Framework\Archive\Tar $tarArchive
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magenizr\Debugger\Helper\Data $data,
        \Magento\Framework\Archive\Tar $tarArchive,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->driverFile = $driverFile;
        $this->directoryList = $directoryList;
        $this->data = $data;
        $this->tarArchive = $tarArchive;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Filesystem\Driver\File
     */
    public function getDriverFile()
    {
        return $this->driverFile;
    }

    /**
     * @return \Magento\Framework\Filesystem\DirectoryList
     */
    public function getDir()
    {
        return $this->directoryList;
    }

    /**
     * @param $dir
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
     * @param $path
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
     * @param $type
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
     * @param $dir
     * @return array
     */
    public function getLabel($dir)
    {
        $labels = [
            'var/log' => __('Logs'),
            'var/report' => __('Report'),
        ];

        return $labels[$dir];
    }

    /**
     * @param $path
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
     * @return bool
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
     * @param $sourceDir
     * @param $destFile
     * @return mixed
     */
    public function packFolder($sourceDir, $destFile)
    {
        if ($this->getDriverFile()->isDirectory($sourceDir)) {
            return $this->tarArchive->pack($sourceDir, $destFile);
        }
    }

    /**
     * @param $bytes
     * @return string
     */
    public function getHumanFilesize($bytes)
    {
        $base = log($bytes) / log(1024);
        $suffix = ['B', 'KB', 'MB', 'GB', 'TB'];
        $fbase = floor($base);

        return ($bytes == 0) ? $bytes : round(pow(1024, $base - floor($base)), 1) . $suffix[$fbase];
    }
}
