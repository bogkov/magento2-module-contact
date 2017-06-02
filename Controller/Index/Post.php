<?php

namespace Bogkov\Contact\Controller\Index;

use Bogkov\Contact\Model\Contact;
use Bogkov\Contact\Model\ContactFactory;
use Bogkov\Contact\Model\ContactMessage;
use Bogkov\Contact\Model\ContactMessageFactory;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Contact\Controller\Index\Post as MagentoPost;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Post
 *
 * @package Bogkov\Contact\Controller\Index
 */
class Post extends MagentoPost
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var ContactFactory
     */
    protected $contactFactory;

    /**
     * @var ContactMessageFactory
     */
    protected $contactMessageFactory;

    /**
     * Post constructor.
     *
     * @param Context               $context
     * @param TransportBuilder      $transportBuilder
     * @param StateInterface        $inlineTranslation
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param ContactFactory        $contactFactory
     * @param ContactMessageFactory $contactMessageFactory
     */
    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ContactFactory $contactFactory,
        ContactMessageFactory $contactMessageFactory
    )
    {
        parent::__construct($context, $transportBuilder, $inlineTranslation, $scopeConfig, $storeManager);

        $this->contactFactory = $contactFactory;
        $this->contactMessageFactory = $contactMessageFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        if (!$post) {
            return $this->_redirect('*/*/');
        }

        $this->inlineTranslation->suspend();

        try {
            $postObject = new DataObject();
            $postObject->setData($post);

            $this->validate($postObject);
            $this->sendMessage($postObject);
            $this->save($postObject);

            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );

            $this->getDataPersistor()->clear('contact_us');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );

            $this->getDataPersistor()->set('contact_us', $post);
        }

        $this->inlineTranslation->resume();

        return $this->_redirect('contact/index');
    }

    /**
     * @param DataObject $postObject
     *
     * @throws \Exception
     */
    protected function validate(DataObject $postObject)
    {
        $error = false;

        if (!\Zend_Validate::is(trim($postObject->getData('name')), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($postObject->getData('comment')), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($postObject->getData('email')), 'EmailAddress')) {
            $error = true;
        }
        if (\Zend_Validate::is(trim($postObject->getData('hideit')), 'NotEmpty')) {
            $error = true;
        }
        if ($error) {
            throw new \Exception();
        }
    }

    /**
     * @param DataObject $postObject
     */
    protected function sendMessage(DataObject $postObject)
    {
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->scopeConfig->getValue(static::XML_PATH_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE))
            ->setTemplateOptions(
                [
                    'area' => FrontNameResolver::AREA_CODE,
                    'store' => Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($this->scopeConfig->getValue(static::XML_PATH_EMAIL_SENDER, ScopeInterface::SCOPE_STORE))
            ->addTo($this->scopeConfig->getValue(static::XML_PATH_EMAIL_RECIPIENT, ScopeInterface::SCOPE_STORE))
            ->setReplyTo($postObject->getData('email'))
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * @param DataObject $postObject
     */
    protected function save(DataObject $postObject)
    {
        /** @var Contact $contactModel */
        $contactModel = $this->contactFactory->create();
        $contactModel->setData('store_id', Store::DEFAULT_STORE_ID);
        $contactModel->setData('status_code', Contact::STATUS_CODE_WAIT_FOR_ANSWER);
        $contactModel->setData('user_name', $postObject->getData('name'));
        $contactModel->setData('user_email', $postObject->getData('email'));
        $contactModel->setData('phone_number', $postObject->getData('telephone'));
        $contactModel->save();

        /** @var ContactMessage $messageModel */
        $messageModel = $this->contactMessageFactory->create();
        $messageModel->setData('contact_id', $contactModel->getId());
        $messageModel->setData('type_code', Contact::TYPE_CODE_CUSTOMER);
        $messageModel->setData('text', $postObject->getData('comment'));
        $messageModel->save();
    }

    /**
     * Get Data Persistor
     *
     * @return DataPersistorInterface
     */
    protected function getDataPersistor()
    {
        if ($this->dataPersistor === null) {
            $this->dataPersistor = ObjectManager::getInstance()
                ->get(DataPersistorInterface::class);
        }

        return $this->dataPersistor;
    }
}
