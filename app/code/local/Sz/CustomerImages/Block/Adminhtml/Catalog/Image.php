<?php

/**
 * Class Sz_CustomerImages_Block_Adminhtml_Catalog_Image
 */
class Sz_CustomerImages_Block_Adminhtml_Catalog_Image extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Sz_CustomerImages_Block_Adminhtml_Catalog_Image constructor.
     */
    public function __construct()
    {
        $this->_blockGroup = 'customerimages';
        $this->_controller = 'adminhtml_catalog_image';
        $this->_headerText = Mage::helper('customerimages')->__('Customer Images');

        parent::__construct();
    }
}