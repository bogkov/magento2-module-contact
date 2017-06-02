<?php

namespace Bogkov\Contact\Model;

use Bogkov\Contact\Model\ResourceModel\Contact as ContactResourceModel;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Contact
 *
 * @package Bogkov\Contact\Model
 */
class Contact extends AbstractModel
{
    /**
     * Status codes
     */
    const STATUS_CODE_WAIT_FOR_ANSWER = 'wait_for_answer';
    const STATUS_CODE_ANSWERED        = 'answered';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ContactResourceModel::class);
    }
}
