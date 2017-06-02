<?php

namespace Bogkov\Contact\Ui\Component\MassAction\ChangeStatusCode;

use Bogkov\Contact\Ui\Component\Listing\Column\StatusCode\Options as StatusCodeOptions;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;

/**
 * Class Options
 *
 * @package Bogkov\Contact\Ui\Component\MassAction\ChangeStatusCode
 */
class Options implements JsonSerializable
{
    /**
     * @var array
     */
    protected $statusCodeOptions;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        StatusCodeOptions $statusCodeOptions,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->statusCodeOptions = $statusCodeOptions;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get action options
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $result = [];

        foreach ($this->statusCodeOptions->toOptionArray() as $option) {
            $result[] = [
                'type' => 'status_code_' . $option['value'],
                'label' => $option['label'],
                'url' => $this->urlBuilder->getUrl(
                    'contact/massAction/changeStatusCode',
                    [
                        'status_code' => $option['value'],
                    ]
                ),
                'confirm' => [
                    'title' => __('Change Status'),
                    'message' => __('Are you sure to change status for selected contacts to "%1"?', $option['label']),
                ],
            ];
        }

        return $result;
    }
}
