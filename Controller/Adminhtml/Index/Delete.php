<?php

namespace Bogkov\Contact\Controller\Adminhtml\Index;

use Bogkov\Contact\Model\Contact;
use Magento\Backend\App\Action;

/**
 * Class Delete
 *
 * @package Bogkov\Contact\Controller\Adminhtml\Index
 */
class Delete extends Action
{
    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('contact_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (null !== $id) {
            try {
                $model = $this->_objectManager->create(Contact::class);
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('The contact has been deleted.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage(__('We can\'t find a contact to delete.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
