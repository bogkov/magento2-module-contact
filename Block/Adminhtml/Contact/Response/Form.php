<?php

namespace Bogkov\Contact\Block\Adminhtml\Contact\Response;

use Bogkov\Contact\Model\ResourceModel\Contact;
use Bogkov\Contact\Model\ResourceModel\ContactMessage;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form\Element\Hidden;
use Magento\Framework\Data\Form\Element\Submit;
use Magento\Framework\Data\Form\Element\Textarea;

/**
 * Class Form
 *
 * @package Bogkov\Contact\Block\Adminhtml\Contact
 */
class Form extends Generic
{
    /**
     * Prepare form
     *
     * @return Generic
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('contact_response_form_fieldset', []);

        $fieldset->addField(
            Contact::FIELD_ID,
            Hidden::class,
            [
                'name' => Contact::FIELD_ID,
                'value' => $this->_coreRegistry->registry('contact')->getData(Contact::FIELD_ID),
            ]
        );

        $fieldset->addField(
            ContactMessage::FIELD_TEXT,
            Textarea::class,
            [
                'name' => ContactMessage::FIELD_TEXT,
                'title' => __('Response'),
                'label' => __('Response'),
                'required' => true,
            ]
        );

        $form->addField(
            'submit',
            Submit::class,
            [
                'name' => 'sent',
                'value' => __('Sent Response'),
                'class' => 'contact-response-button',
            ]
        );

        $form->setMethod('post');
        $form->setId('contact_response_form');
        $form->setUseContainer(true);
        $form->setAction($this->getUrl('contact/index/response'));

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
