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
    const FIELD_ID         = 'contact_message_id';
    const FIELD_CONTACT_ID = 'contact_id';
    const FIELD_TYPE_CODE  = 'type_code';
    const FIELD_TEXT       = 'text';
    const FIELD_CREATED_AT = 'created_at';

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
