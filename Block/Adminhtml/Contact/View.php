<?php

namespace Bogkov\Contact\Block\Adminhtml\Contact;

use Bogkov\Contact\Model\Contact;
use Bogkov\Contact\Model\ContactMessageFactory;
use Bogkov\Contact\Model\ResourceModel\ContactMessage;
use Bogkov\Contact\Model\ResourceModel\ContactMessage\Collection as ContactMessageCollection;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Class View
 *
 * @package Bogkov\Contact\Block\Adminhtml\Contact
 */
class View extends Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var ContactMessageFactory
     */
    protected $contactMessageFactory;

    /**
     * View constructor.
     *
     * @param Context               $context
     * @param Registry              $registry
     * @param ContactMessageFactory $contactMessageFactory
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ContactMessageFactory $contactMessageFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->registry = $registry;
        $this->contactMessageFactory = $contactMessageFactory;
    }

    /**
     * Contact
     *
     * @return Contact
     */
    public function getContact()
    {
        return $this->registry->registry('contact');
    }

    /**
     * Contact messages
     *
     * @return ContactMessageCollection
     */
    public function getContactMessages()
    {
        $contact = $this->getContact();

        $messageModel = $this->contactMessageFactory->create();

        /** @var ContactMessageCollection $messageCollection */
        $messageCollection = $messageModel->getCollection();
        $messageCollection->addFilter(ContactMessage::FIELD_CONTACT_ID, $contact->getId());

        return $messageCollection;
    }

    /**
     * Format date time
     *
     * @param string $dateTime
     * @param string $format
     *
     * @return string
     */
    public function formatDateTime($dateTime, $format)
    {
        return $this->_localeDate->date(new \DateTime($dateTime))->format($format); // TODO timezone
    }
}
