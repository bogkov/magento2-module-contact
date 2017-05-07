<?php

namespace Bogkov\Contact\Ui\Component\Listing\Column\StatusCode;

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
                'value' => 1,
                'label' => __('Wait for answer'),
            ],
            [
                'value' => 2,
                'label' => __('Answered'),
            ]
        ];
    }
}
