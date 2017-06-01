<?php

namespace Bogkov\Contact\Controller\Adminhtml\Index;

use Bogkov\Contact\Config;
use Bogkov\Contact\Model\ContactFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class View
 *
 * @package Bogkov\Contact\Controller\Adminhtml\Index
 */
class View extends Action
{
    /**
     * @var ContactFactory
     */
    protected $contactFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @param Context        $context
     * @param ContactFactory $contactFactory
     * @param Registry       $registry
     * @param PageFactory    $pageFactory
     */
    public function __construct(
        Context $context,
        ContactFactory $contactFactory,
        Registry $registry,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);

        $this->contactFactory = $contactFactory;
        $this->registry = $registry;
        $this->pageFactory = $pageFactory;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(Config::NAME . '::view');
    }

    /**
     * Contact list action
     *
     * @return Page|ResponseInterface
     */
    public function execute()
    {
        $contactId = $this->getRequest()->getParam('contact_id');

        if (null === $contactId) {
            return $this->redirectToIndexWithMessage(__('Contact ID not specified'));
        }

        $contact = $this->contactFactory->create();

        $contact->load($contactId);

        if (null === $contact->getId()) {
            return $this->redirectToIndexWithMessage(
                sprintf(
                    __('Contact with #%s no longer exists'),
                    $contactId
                )
            );
        }

        $this->registry->register('contact', $contact);

        /** @var Page $page */
        $page = $this->pageFactory->create();
        $page->setActiveMenu(Config::NAME . '::contact');

        $breadcrumb = sprintf(
            __('View Contact #%s from %s'),
            $contact->getId(),
            $contact->getData('user_name')
        );
        $page->addBreadcrumb($breadcrumb, $breadcrumb);

        return $page;
    }

    /**
     * @param string $message
     *
     * @return ResponseInterface
     */
    protected function redirectToIndexWithMessage($message)
    {
        $this->messageManager->addErrorMessage($message);

        return $this->_redirect('contact/index');
    }
}
