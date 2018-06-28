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

use \Magento\Framework\App\Request\Http;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Data constructor.
     * @param Http $request
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        Http $request,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);

        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function isModuleEnabled()
    {
        return $this->getScopeConfig('system_tools/magenizr_debugger/enabled');
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getScopeConfig($path)
    {
        return $this->scopeConfig->getValue($path);
    }

    /**
     * @param $param
     * @return mixed
     */
    public function getParam($param)
    {
        return $this->request->getParam($param);
    }

    /**
     * @param $dir
     */
    public function getDownloadUrl($dir)
    {
        $url = $this->urlBuilder->getUrl('magenizr_debugger/download/file', [
            '_current' => true,
            '_query' => ['dir' => $dir, 'type' => basename($dir)]
        ]);

        return $url;
    }
}
