<?php

namespace SFS\Locations\Block\Adminhtml\Stores\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Directory\Model\ResourceModel\Region\Collection as RegionCollection;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use SFS\Locations\Model\Store;

class Info extends Generic implements TabInterface
{
    /**
     * @var RegionCollection
     */
    protected $_regionCollection;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param RegionCollection $regionCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        RegionCollection $regionCollection,
        array $data = []
    ) {
        $this->_regionCollection = $regionCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @return Form
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var $model Store */
        $model = $this->_coreRegistry->registry('locations');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('stores_');
        $form->setFieldNameSuffix('stores');

        $fieldset = $form->addFieldset(
            'store_information_fieldset',
            ['legend' => __('Display Settings'),
                'class' => 'fieldset-wide',
                'expanded'  => true
            ]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'store_id']
            );
        }

        $fieldset->addField(
            'store_hours',
            'hidden',
            ['name' => 'store_hours']
        );

        $fieldset->addField(
            'name',
            'text',
            [
                'name'        => 'name',
                'label'    => __('Store Name'),
                'required'     => true
            ]
        );

        $fieldset->addField(
            'image_url',
            'text',
            [
                'name'        => 'image_url',
                'label'    => __('Store Image Url'),
                'required'     => false
            ]
        );

        $fieldset->addField('color', 'text', array(
            'label'     => __('Border Color'),
            'class'  => 'jscolor {hash:true,refine:false}',
            'required'  => false,
            'name'      => 'color',
            'note' => __('Specify the color of the top border for this location that displays on the front-end.'),
        ));

        $fieldset = $form->addFieldset(
            'store_contact_fieldset',
            ['legend' => __('Contact Information'),
                'class' => 'fieldset-wide',
                'expanded'  => false
            ]
        );

        $fieldset->addField(
            'street_address',
            'text',
            [
                'name'        => 'street_address',
                'label'    => __('Street Address'),
                'required'     => true
            ]
        );

        $fieldset->addField(
            'city',
            'text',
            [
                'name'        => 'city',
                'label'    => __('City'),
                'required'     => true
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __("Status"),
                'class' => 'required-entry',
                'required' => 'true',
                'name' => 'is_active',
                'value' => '1',
                'values'    => [
                    ['label' => 'Enabled', 'value' => '1'],
                    ['label' => 'Disabled', 'value' => '0']
                ]
            ]
        );

        // Get array of US regions for state selection
        $regionCollection = $this->_regionCollection->addFieldtoFilter('country_id', ['eq' => 'US'])->toOptionArray();
        $regions = array_column($regionCollection, 'label', 'title');

        $fieldset->addField(
            'state',
            'select',
            [
                'name'        => 'state',
                'label'    => __('State'),
                'required'     => true,
                'value' => 'Please select a region, state or province.',
                'values' => $regions
            ]
        );

        $fieldset->addField(
            'zip_code',
            'text',
            [
                'name'        => 'zip_code',
                'label'    => __('Zip Code'),
                'required'     => true
            ]
        );

        $fieldset->addField(
            'phone_number',
            'text',
            [
                'name'        => 'phone_number',
                'label'    => __('Phone Number'),
                'required'     => true
            ]
        );

        $fieldset->addField(
            'email',
            'text',
            [
                'name'        => 'email',
                'label'    => __('Email'),
                'required'     => true
            ]
        );

        $fieldset = $form->addFieldset(
            'store_social_media_fieldset',
            ['legend' => __('Social Media Links'),
                'class' => 'fieldset-wide',
                'expanded'  => false
            ]
        );

        $fieldset->addField(
            'facebook_url',
            'text',
            [
                'name'        => 'facebook_url',
                'label'    => __('Facebook Url'),
                'required'     => false
            ]
        );

        $fieldset->addField(
            'twitter_url',
            'text',
            [
                'name'        => 'twitter_url',
                'label'    => __('Twitter Url'),
                'required'     => false
            ]
        );

        $fieldset->addField(
            'pinterest_url',
            'text',
            [
                'name'        => 'pinterest_url',
                'label'    => __('Pinterest Url'),
                'required'     => false
            ]
        );

        $fieldset->addField(
            'instagram_url',
            'text',
            [
                'name'        => 'instagram_url',
                'label'    => __('Instagram Url'),
                'required'     => false
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare form Html. call the phtml file with form.
     *
     * @return string
     */
    public function getFormHtml()
    {
        // get the current form as html content.
        $html = parent::getFormHtml();
        //Append the phtml file after the form content.
        $html .= $this->setTemplate('SFS_Locations::stores/store_hours.phtml')->toHtml();
        return $html;
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Stores Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Stores Info');
    }

    /**
     * Can this tab be shown
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Is this tab visible
     */
    public function isHidden()
    {
        return false;
    }
}
