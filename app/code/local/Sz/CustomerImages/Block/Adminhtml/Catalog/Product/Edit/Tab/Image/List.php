<?php

/**
 * Class Sz_CustomerImages_Block_Adminhtml_Catalog_Product_Edit_Tab_Image_List
 */
class Sz_CustomerImages_Block_Adminhtml_Catalog_Product_Edit_Tab_Image_List extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Sz_CustomerImages_Block_Adminhtml_Catalog_Product_Edit_Tab_Image_List constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('image_list_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('id');
        $this->getProduct();
        $this->setDefaultFilter(['image_ids' => 1]);
    }

    /**
     * @param $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'image_ids') {
            $imageIds = $this->_getSelectedImages();
            if (empty($imageIds)) {
                $imageIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('id', array('in' => $imageIds));
            } else {
                $this->getCollection()->addFieldToFilter('id', array('nin' => $imageIds));
            }
        } else {
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
         * @var Sz_CustomerImages_Model_Resource_Image_Collection $collection
         */
        $collection = Mage::getResourceModel('customerimages/image_collection');
        $collection->getAllow();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $helper = Mage::helper('customerimages');

        $this->addColumn('image_ids', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'index' => 'id',
            'field_name' => 'image_ids' . '[]',
            'align' => 'center',
            'values' => $this->_getSelectedImages()
        ));

        $this->addColumn('id', array(
            'header' => $helper->__('ID'),
            'sortable' => true,
            'width' => 60,
            'index' => 'id'
        ));

        $this->addColumn('customer_id', array(
            'header' => $helper->__('Customer'),
            'index' => 'customer_id'
        ));

        $this->addColumn('label', array(
            'header' => $helper->__('Label'),
            'index' => 'label'
        ));

        $this->addColumn('file', array(
            'header' => $helper->__('File'),
            'index' => 'file'
        ));

        $this->addExportType('*/*/exportImagesCsv', $helper->__('CSV'));
        $this->addExportType('*/*/exportImagesExcel', $helper->__('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * Retirve currently edited product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function getProduct()
    {
        $session = Mage::getSingleton('customer/session');
        if ($product = Mage::registry('current_product')) {
            $session->setData('product_data', $product);
        } else {
            $product = $session->getData('product_data');
        }

        return $product;
    }

    /**
     * @return array
     */
    protected function _getSelectedImages()
    {
        $imageIds = [];
        $collection = Mage::getResourceModel('customerimages/image_collection');
        $collection
            ->loadByProductId($this->getProduct()->getId())
            ->getVisible();

        foreach ($collection as $item) {
            $imageIds[] = $item->getId();
        }

        return $imageIds;
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/image/gridImageList', array('_current' => true));
    }
}