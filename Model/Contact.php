<?php

namespace Bogkov\Contact\Model;

use Bogkov\Contact\Model\ResourceModel\Contact as ResourceModel;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Contact
 *
 * @package Bogkov\Contact\Model
 */
class Contact extends AbstractModel
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
