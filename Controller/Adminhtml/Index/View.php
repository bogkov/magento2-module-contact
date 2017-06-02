<?php

namespace Bogkov\Contact\Controller\Adminhtml\Index;

use Bogkov\Contact\Config;
use Bogkov\Contact\Model\ContactFactory;
use Bogkov\Contact\Model\ResourceModel\Contact;
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
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = Config::NAME . '::view';

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
     * View constructor.
     *
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
     * Contact list action
     *
     * @return Page|ResponseInterface
     */
    public function execute()
    {
        $contactId = $this->getRequest()->getParam(Contact::FIELD_ID);

        if (null === $contactId) {
            $this->messageManager->addErrorMessage(__('Contact ID not specified'));
            return $this->_redirect('contact/index');
        }

        $contact = $this->contactFactory->create();

        $contact->load($contactId);

        if (null === $contact->getId()) {
            $this->messageManager->addErrorMessage(__('Contact with #%1 no longer exists', $contactId));
            return $this->_redirect('contact/index');
        }

        $this->registry->register('contact', $contact);

        /** @var Page $page */
        $page = $this->pageFactory->create();
        $page->setActiveMenu(Config::NAME . '::contact');
        $page->getConfig()->getTitle()->prepend(__('View Contact #%1', $contact->getId()));

        return $page;
    }
}
