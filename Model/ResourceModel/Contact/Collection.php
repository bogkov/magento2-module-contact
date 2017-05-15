<?php

namespace Bogkov\Contact\Model\ResourceModel\Contact;

use Bogkov\Contact\Model\Contact;
use Bogkov\Contact\Model\ResourceModel\Contact as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Bogkov\Contact\Model\ResourceModel\Contact
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
        $this->_init(Contact::class, ResourceModel::class);
    }
}
