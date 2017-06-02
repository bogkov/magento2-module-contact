<?php

namespace Bogkov\Contact\Controller\Adminhtml\Index;

use Bogkov\Contact\Model\Contact as ContactModel;
use Bogkov\Contact\Model\ResourceModel\Contact as ContactResourceModel;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

/**
 * Class Delete
 *
 * @package Bogkov\Contact\Controller\Adminhtml\Index
 */
class Delete extends Action
{
    /**
     * Dispatch request
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $contactId = $this->getRequest()->getParam(ContactResourceModel::FIELD_ID);

        if (null !== $contactId) {
            try {
                $model = $this->_objectManager->create(ContactModel::class);
                $model->load($contactId);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('The contact has been deleted.'));
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage(__('We can\'t find a contact to delete.'));
        }

        return $this->_redirect('contact/index');
    }
}
