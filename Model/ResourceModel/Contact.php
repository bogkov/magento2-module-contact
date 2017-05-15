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
    const TABLE = 'contact';
    const FIELD_ID = 'contact_id';

    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(static::TABLE, static::FIELD_ID);
    }
}
