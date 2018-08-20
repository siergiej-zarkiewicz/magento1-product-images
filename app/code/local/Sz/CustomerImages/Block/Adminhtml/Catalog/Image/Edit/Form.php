<?php

class Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $session = Mage::getSingleton('adminhtml/session');
        if ($session->getFormData()) {
            $data = $session->getFormData();
            $session->setFormData(null);
        } elseif ($registryData = Mage::registry('image_data')) {
            $data = $registryData->getData();
        }

        $url = Mage::getSingleton('customerimages/image')->getImageUrl($data['file']);

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/edit', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));
        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset(
            'image_form',
            ['legend' => Mage::helper('customerimages')->__('Image information')]
        );

        $fieldset->addType('product_grid', 'Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Edit_Renderer_Fieldset_Productgrid');

        $fieldset->addField('image', 'label', [
            'label' => Mage::helper('customerimages')->__('Image'),
            'required' => false,
            'after_element_html' => '<img src="' . $url . '" alt="Image not available" height="120" width="120" />',
        ]);

        $fieldset->addField('label', 'text', [
            'label' => Mage::helper('customerimages')->__('Label'),
            'required' => false,
            'name' => 'label',
        ]);

        $fieldset->addField('allow', 'checkbox', [
            'label' => Mage::helper('customerimages')->__('Allow'),
            'required' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'name' => 'allow',
        ])->setChecked((bool)$data['allow']);

        $fieldset->addField('product_ids', 'product_grid', array(
            'label' => Mage::helper('customer')->__('Products'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'product_ids',
            'onclick' => '',
            'onchange' => '',
            'disabled' => false,
            'readonly' => false,
            'tabindex' => 1,
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }

}