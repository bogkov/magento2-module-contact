<?php

namespace Bogkov\Contact\Controller\Adminhtml\Index;

use Bogkov\Contact\Controller\Index\Post;
use Bogkov\Contact\Model\Contact;
use Bogkov\Contact\Model\ResourceModel\Contact as ContactResourceModel;
use Bogkov\Contact\Model\ResourceModel\ContactMessage as MessageResourceModel;
use Bogkov\Contact\Model\ContactFactory;
use Bogkov\Contact\Model\ContactMessage;
use Bogkov\Contact\Model\ContactMessageFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Response
 *
 * @package Bogkov\Contact\Controller\Adminhtml\Index
 */
class Response extends Post
{
    /**
     * @var ContactFactory
     */
    protected $contactFactory;

    /**
     * @var ContactMessageFactory
     */
    protected $contactMessageFactory;

    /**
     * @var Session
     */
    protected $authSession;

    /**
     * Response constructor.
     *
     * @param Context               $context
     * @param TransportBuilder      $transportBuilder
     * @param StateInterface        $inlineTranslation
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param ContactFactory        $contactFactory
     * @param ContactMessageFactory $contactMessageFactory
     * @param Session               $authSession
     */
    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ContactFactory $contactFactory,
        ContactMessageFactory $contactMessageFactory,
        Session $authSession
    )
    {
        parent::__construct($context, $transportBuilder, $inlineTranslation, $scopeConfig, $storeManager, $contactFactory, $contactMessageFactory);

        $this->authSession = $authSession;
    }

    /**
     * Dispatch request
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        if (!$post) {
            return $this->_redirect('contact/index');
        }

        $postObject = new DataObject();
        $postObject->setData($post);

        $contactId = $postObject->getData(ContactResourceModel::FIELD_ID);

        try {
            /** @var Contact $contactModel */
            $contactModel = $this->contactFactory->create();
            $contactModel->load($contactId);
            $postObject->setData('contact', $contactModel);

            $this->validate($postObject);
            $this->sendMessage($postObject);
            $this->addMessageToContact($postObject);
            $contactModel->setData(ContactResourceModel::FIELD_STATUS_CODE, Contact::STATUS_CODE_ANSWERED);
            $contactModel->save();

            $this->messageManager->addSuccessMessage(__('Response message successfully send'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this->_redirect('contact/index/view', [ContactResourceModel::FIELD_ID => $contactId]);
    }

    /**
     * Validate
     *
     * @param DataObject $postObject
     *
     * @throws \Exception
     */
    protected function validate(DataObject $postObject)
    {
        if (!\Zend_Validate::is(trim($postObject->getData('text')), 'NotEmpty')) {
            throw new \Exception(__('Response message can\'t be empty'));
        }
    }

    /**
     * Send message
     *
     * @param DataObject $postObject
     */
    protected function sendMessage(DataObject $postObject)
    {
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier('contact_email_response_form')
            ->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(
                [
                    'text' => $postObject->getData(MessageResourceModel::FIELD_TEXT),
                ]
            )
            ->setFrom(
                [
                    'email' => $this->authSession->getUser()->getEmail(),
                    'name' => $this->authSession->getUser()->getName()
                ]
            )
            ->addTo($postObject->getData('contact')->getData(ContactResourceModel::FIELD_USER_EMAIL))
            ->setReplyTo($this->authSession->getUser()->getEmail(), $this->authSession->getUser()->getName())
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * Add message
     *
     * @param DataObject $postObject
     */
    protected function addMessageToContact(DataObject $postObject)
    {
        /** @var ContactMessage $messageModel */
        $messageModel = $this->contactMessageFactory->create();
        $messageModel->setData(MessageResourceModel::FIELD_CONTACT_ID, $postObject->getData('contact')->getId());
        $messageModel->setData(MessageResourceModel::FIELD_TYPE_CODE, ContactMessage::TYPE_CODE_OWNER);
        $messageModel->setData(MessageResourceModel::FIELD_TEXT, $postObject->getData(MessageResourceModel::FIELD_TEXT));
        $messageModel->save();
    }
}
