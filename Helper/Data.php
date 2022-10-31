<?php
declare(strict_types=1);
/**
 * Magenizr Debugger
 *
 * @copyright   Copyright (c) 2018 - 2022 Magenizr (https://www.magenizr.com)
 * @license     https://www.magenizr.com/license Magenizr EULA
 */

namespace Magenizr\Debugger\Helper;

use \Magento\Framework\App\Request\Http;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Http
     */
    private $request;

    /**
     * Init Constructor
     *
     * @param Http $request
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        Http $request,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->request = $request;

        parent::__construct($context);
    }

    /**
     * Check if access is restricted ( by IP )
     *
     * @return bool
     */
    public function isAccessRestricted()
    {
        if (empty($this->getScopeConfig('restriction_ip')) || empty(trim($this->getScopeConfig('restriction_ip')))) {
            return false;
        }

        $restrictedIps = array_map('trim', explode(',', $this->getScopeConfig('restriction_ip')));

        if (in_array($this->remoteAddress->getRemoteAddress(), $restrictedIps)) {
            return false;
        }

        return true;
    }

    /**
     * Check if module is enabled
     *
     * @return mixed
     */
    public function isModuleEnabled()
    {
        return $this->getScopeConfig('enabled');
    }

    /**
     * Get param from URL
     *
     * @param string $param
     * @return mixed
     */
    public function getParam($param)
    {
        return $this->request->getParam($param);
    }

    /**
     * Get path by type
     *
     * @param string $type
     * @return bool|mixed
     */
    public function getPathByType($type)
    {
        $typesMapping = [
            'log' => 'var/log',
            'report' => 'var/report',
        ];

        return (array_key_exists($type, $typesMapping)) ? $typesMapping[$type] : false;
    }

    /**
     * Get scope value from core_config_data
     *
     * @param string $field
     * @return mixed
     */
    public function getScopeConfig($field)
    {
        return $this->scopeConfig->getValue(
            sprintf('dev/magenizr_debugger/%s', $field),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
