<?php

namespace Bogkov\Contact\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Contact
 *
 * @package Bogkov\Contact\Model\ResourceModel
 */
class Contact extends AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('contact', 'contact_id');
    }
}
