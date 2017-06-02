<?php

namespace Bogkov\Contact\Controller\Adminhtml\MassAction;

use Bogkov\Contact\Model\Contact;
use Bogkov\Contact\Model\ResourceModel\Contact as ContactResourceModel;
use Bogkov\Contact\Model\ResourceModel\Contact\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

/**
 * Class MassChangeStatus
 *
 * @package Bogkov\Contact\Controller\Adminhtml\Index
 */
class ChangeStatusCode extends Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context           $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        try {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter(
                ContactResourceModel::FIELD_ID,
                [
                    'id' => (array)$this->getRequest()->getParam('selected'),
                ]
            );

            $counter = 0;

            /** @var Contact $contact */
            foreach ($collection->getItems() as $contact) {
                $contact->setData(
                    ContactResourceModel::FIELD_STATUS_CODE,
                    $this->getRequest()->getParam(ContactResourceModel::FIELD_STATUS_CODE)
                );
                $contact->save();

                $counter++;
            }

            if (false === empty($counter)) {
                $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were updated.', $counter));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this->_redirect('contact/index');
    }
}
