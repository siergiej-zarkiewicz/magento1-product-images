<?php

class Sz_CustomerImages_Block_Adminhtml_Catalog_Image_New_Renderer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Sz_CustomerImages_Block_Adminhtml_Catalog_Image_New_Renderer_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('image_product_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(false);
        $this->getImage();
        $this->setDefaultFilter(['product_ids' => 1]);

    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        /**
         * @var Mage_Catalog_Model_Resource_Product_Collection $collection
         */
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->addAttributeToSelect([
            'name',
            'sku',
            'status'
        ]);

        $this->setCollection($collection);
        return parent::_prepareCollection();

    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('product_ids', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'index' => 'entity_id',
            'field_name' => 'product_ids[]',
            'align' => 'center',
        ));

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('customer')->__('ID'),
            'width' => '50px',
            'index' => 'entity_id',
            'type' => 'number',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('customer')->__('Name'),
            'width' => '200px',
            'index' => 'name'
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('customer')->__('Sku'),
            'width' => '100',
            'index' => 'sku'
        ));

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/gridNew', array('_current'=>true));
    }

    /**
     * @return $this
     */
    protected function _prepareGrid()
    {
        $this->_prepareColumns();
        $this->_prepareMassactionBlock();
        $this->_prepareCollection();
        return $this;
    }

}