<?php

namespace Bogkov\Contact\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Actions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl('contact/*/view', ['contact_id' => $item['contact_id'],]),
                    'label' => __('View'),
                    'hidden' => false,
                ];

                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl('contact/*/delete', ['contact_id' => $item['contact_id'],]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete #${ $.$data.contact_id }'),
                        'message' => __('Are you sure you wan\'t to delete a #${ $.$data.contact_id } record?')
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
