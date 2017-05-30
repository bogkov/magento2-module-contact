<?php

namespace Bogkov\Contact\Model\ResourceModel\ContactMessage;

use Bogkov\Contact\Model\ContactMessage as ContactMessageModel;
use Bogkov\Contact\Model\ResourceModel\ContactMessage as ContactMessageResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Bogkov\Contact\Model\ResourceModel\ContactMessage
 */
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ContactMessageModel::class, ContactMessageResourceModel::class);
    }
}
