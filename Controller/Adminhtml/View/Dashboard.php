<?php
declare(strict_types=1);
/**
 * Magenizr Debugger
 *
 * @copyright   Copyright (c) 2018 - 2022 Magenizr (https://www.magenizr.com)
 * @license     https://www.magenizr.com/license Magenizr EULA
 */

namespace Magenizr\Debugger\Controller\Adminhtml\View;

use Magento\Catalog\Model;
use Magento\Framework\Exception\CouldNotDeleteException;

class Dashboard extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Magenizr_Debugger::debugger_dashboard';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $pageFactory;

    /**
     * Init Constructor
     *
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
     * Init execute
     *
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
