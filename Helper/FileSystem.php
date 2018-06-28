<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     https://www.magenizr.com/license/ Magenizr EULA
 */

namespace Magenizr\Debugger\Helper;

/**
 * Class FileSystem
 * @package Magenizr\Debugger\Helper
 */
class FileSystem extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $dir;

    protected $lastReportFile;

    /**
     * FileSystem constructor.
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);

        $this->dir = $dir;
        $this->setLastReportFile();
    }

    /**
     * @return \Magento\Framework\Filesystem\DirectoryList
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param $dir
     * @return mixed
     */
    public function getPath($dir = '')
    {

        $absolutePath = $this->getDir()->getRoot();

        if (strlen($dir) > 1)
            $absolutePath = $absolutePath . '/' . $dir;

        return $absolutePath;
    }

    /**
     * @param $dir
     * @return int
     */
    public function getDirSize($dir)
    {
        $size = 0;
        $files = glob($dir . '/*');

        foreach($files as $path) {
            is_file($path) && $size += filesize($path);
            is_dir($path) && $this->getDirSize($path);
        }

        return $size;
    }

    /**
     * @param $dir
     * @return array
     */
    public function getDirDetails($dir)
    {
        $path = $this->getPath($dir);
        $size = $this->getDirSize($path);

        $details = [];
        $details['path'] = $path;
        $details['size'] = $this->getHumanFilesize($size);
        $details['name'] = $this->getLabel($dir);

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
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    public function getHumanFilesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    /**
     * @param $dir
     */
    public function getFilesFromFolder($path)
    {
        if (is_readable($path)) {

            $list = [];

            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS));

            foreach ($iterator as $file) {

                $ctime = $file->getCTime();

                $list[$ctime] = [
                    'name' => $file->getFileName(),
                    'realpath' => $file->getRealPath()
                ];
            }

            return $list;
        }

        return [];
    }

    protected function setLastReportFile() {

        // Absolute path to dir
        $path = $this->getPath('var/report');

        // List of files
        $list = $this->getFilesFromFolder($path);

        if (count($list) > 0) {

            sort($list);

            // At least one file

            $file = array_values($list)[0];

            if (is_readable($file['realpath'])) {
                $this->lastReportFile = $file['realpath'];
            }
        }
    }

    /**
     * @return bool
     */
    public function getLastReportFile()
    {
        if (is_readable($this->lastReportFile)) {
            return $this->lastReportFile;
        }

        return false;
    }

    /**
     * @return bool|string
     */
    public function getLastReportFileContent()
    {
        if ($file = $this->getLastReportFile()) {
            return file_get_contents($file);
        }

        return false;
    }

    /**
     * @param $sourceDir
     * @param $destFile
     */
    public function addDirToZip($sourceDir, $destFile)
    {
        if (is_dir($sourceDir)) {

            $zip = new \ZipArchive();

            if ($zip->open($destFile, \ZIPARCHIVE::CREATE) !== TRUE) {
                $this->messageManager->addError(__('Could not open zip file archive. Make sure %1 is writeable.', $destFile));
                return;
            }

            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sourceDir, \RecursiveIteratorIterator::SELF_FIRST));

            foreach ($files as $file) {

                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($sourceDir . '/', '', $file . '/'));
                }
                else if (is_file($file) === true)
                {
                    $zip->addFromString(str_replace($sourceDir . '/', '', $file), file_get_contents($file));
                }
            }

            $zip->close();
        }
    }

}
