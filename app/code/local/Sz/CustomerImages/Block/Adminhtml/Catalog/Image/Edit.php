<?php

class Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Edit constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'customerimages';
        $this->_controller = 'adminhtml_catalog_image';
        $this->_mode = 'edit';
        $this->_updateButton('save', 'label', Mage::helper('customerimages')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('customerimages')->__('Delete'));
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
       return Mage::helper('customerimages')->__('Edit image');
    }
}

