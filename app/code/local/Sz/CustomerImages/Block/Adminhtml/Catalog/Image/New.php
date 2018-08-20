<?php

class Sz_CustomerImages_Block_Adminhtml_Catalog_Image_New extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'customerimages';
        $this->_controller = 'adminhtml_catalog_image';
        $this->_mode = 'new';
        $this->_updateButton('save', 'label', Mage::helper('customerimages')->__('Save'));
    }

    public function getHeaderText()
    {
        return Mage::helper('customerimages')->__('Add image');
    }
}

