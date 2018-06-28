<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     https://www.magenizr.com/license/ Magenizr EULA
 */

namespace Magenizr\Debugger\Controller\Adminhtml\View;

use Magento\Catalog\Model;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class Dashboard
 * @package Magenizr\Debugger\Controller\Adminhtml\View
 */
class Dashboard extends \Magento\Backend\App\Action
{

    /**
     * Dashboard constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magenizr\Debugger\Helper\Data $helper
     * @param \Magenizr\Debugger\Helper\Php $php
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magenizr\Debugger\Helper\Data $helper,
        \Magenizr\Debugger\Helper\Php $php
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->helper = $helper;
        $this->php = $php;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        // Page factory
        $resultPage = $this->pageFactory->create();

        // Update title
        $resultPage->getConfig()->getTitle()->set(
            __('Debugger')
        );

        return $resultPage;
    }

}
