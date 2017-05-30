<?php

namespace Bogkov\Contact\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ContactMessage
 *
 * @package Bogkov\Contact\Model\ResourceModel
 */
class ContactMessage extends AbstractDb
{
    const TABLE = 'contact_message';

    /**
     * Fields
     */
    const FIELD_ID = 'contact_message_id';

    /**
     * Type codes
     */
    const TYPE_CODE_CUSTOMER = 'customer';
    const TYPE_CODE_OWNER    = 'owner';

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
