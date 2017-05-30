<?php

namespace Bogkov\Contact\Ui\Component\Listing\Column\StatusCode;

use Bogkov\Contact\Model\ResourceModel\Contact;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Options
 *
 * @package Bogkov\Contact\Ui\Component\Listing\Column\StatusCode
 */
class Options implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => Contact::STATUS_CODE_WAIT_FOR_ANSWER,
                'label' => __('Wait for answer'),
            ],
            [
                'value' => Contact::STATUS_CODE_ANSWERED,
                'label' => __('Answered'),
            ]
        ];
    }
}
