<?php

class Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Edit_Renderer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Edit_Renderer_Grid constructor.
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
     * @param $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'product_ids') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            }
            else {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
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
        $collection->joinTable(
            'customerimages/product',
            'product_id = entity_id',
            [
                'product_id' => 'product_id',
                'image_id' => 'image_id',
                'enable' => 'enable',
            ],
            ['image_id' => $this->getImage()->getId()],
            'left'
            );

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
            'values' => $this->_getSelectedProducts()
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
        return $this->getUrl('*/*/gridEdit', array('_current'=>true));
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

    /**
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $productIds = [];
        $image = $this->getImage();
        if($image) {
            $collection = $image->getProducts();
            if ($collection) {
                foreach ($collection as $item) {
                    $productIds[] = $item->getProductId();
                }
            }
        }

        return $productIds;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        $session = Mage::getSingleton( 'customer/session');

        if($imageData = Mage::registry('image_data')) {
            $session->setData('image_data', $imageData);
        } else {
            $imageData = $session->getData('image_data');
        }

        return $imageData;
    }

}