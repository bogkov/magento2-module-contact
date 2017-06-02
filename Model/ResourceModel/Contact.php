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

    /**
     * Fields
     */
    const FIELD_ID           = 'contact_id';
    const FIELD_STORE_ID     = 'store_id';
    const FIELD_STATUS_CODE  = 'status_code';
    const FIELD_USER_NAME    = 'user_name';
    const FIELD_USER_EMAIL   = 'user_email';
    const FIELD_PHONE_NUMBER = 'phone_number';
    const FIELD_CREATED_AT   = 'created_at';

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
