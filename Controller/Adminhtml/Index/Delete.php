<?php

namespace Bogkov\Contact\Controller\Adminhtml\Index;

use Bogkov\Contact\Model\Contact;
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
     * @return ResponseInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('contact_id');

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

        return $this->_redirect('contact/index');
    }
}
