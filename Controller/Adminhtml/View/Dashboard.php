<?php
/**
 * Magenizr Debugger
 *
 * @category    Magenizr
 * @package     Magenizr_Debugger
 * @copyright   Copyright (c) 2018 Magenizr (http://www.magenizr.com)
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
    const ADMIN_RESOURCE = 'Magenizr_Debugger::debugger_dashboard';

    /**
     * Dashboard constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();

        $resultPage->getConfig()->getTitle()->set(
            __('Debugger')
        );

        return $resultPage;
    }
}
