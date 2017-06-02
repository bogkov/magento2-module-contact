<?php

namespace Bogkov\Contact\Controller\Adminhtml\Index;

use Bogkov\Contact\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * @package Bogkov\Contact\Controller\Adminhtml\Index
 */
class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = Config::NAME . '::contact';

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * Index constructor.
     *
     * @param Context     $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * Dispatch request
     *
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu(Config::NAME . '::contact');
        $resultPage->getConfig()->getTitle()->prepend(__('Contact Us'));

        return $resultPage;
    }
}
