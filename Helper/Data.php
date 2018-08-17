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

use \Magento\Framework\App\Request\Http;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Data constructor.
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
     * @return bool
     */
    public function isAccessRestricted()
    {
        if (empty(trim($this->getScopeConfig('restriction_ip')))) {
            return false;
        }

        $restrictedIps = array_map('trim', explode(',', $this->getScopeConfig('restriction_ip')));

        if (in_array($this->remoteAddress->getRemoteAddress(), $restrictedIps)) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function isModuleEnabled()
    {
        return $this->getScopeConfig('enabled');
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
     * @param $type
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
     * @param $field
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
