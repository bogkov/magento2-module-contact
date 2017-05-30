<?php

namespace Bogkov\Contact\Model;

use Bogkov\Contact\Model\ResourceModel\ContactMessage as ContactMessageResourceModel;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ContactMessage
 *
 * @package Bogkov\Contact\Model
 */
class ContactMessage extends AbstractModel
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ContactMessageResourceModel::class);
    }
}
