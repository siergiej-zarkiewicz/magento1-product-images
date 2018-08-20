<?php

/**
 * Class Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Grid
 */
class Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Sz_CustomerImages_Block_Adminhtml_Catalog_Image_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('customerimagesImageGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
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
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $helper = Mage::helper('customerimages');

        $this->addColumn('image', array(
            'header' => $helper->__('Image'),
            'index'  => 'file',
            'width'  => '70',
            'filter' => false,
            'sortable'  => false,
            'renderer' => 'Sz_CustomerImages_Block_Adminhtml_Template_Grid_Renderer_Image'
        ));

        $this->addColumn('customer_id', array(
            'header' => $helper->__('Customer'),
            'index'  => 'customer_id'
        ));

        $this->addColumn('label', array(
            'header' => $helper->__('Label'),
            'index'  => 'label'
        ));

        $this->addColumn('allow', array(
            'header' => $helper->__('Allow'),
            'index'  => 'allow',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('customerimages')->__('Yes'),
                '0' => Mage::helper('customerimages')->__('No'),
            ),
            'align' => 'center',
        ));

        $this->addExportType('*/*/exportImagesCsv', $helper->__('CSV'));
        $this->addExportType('*/*/exportImagesExcel', $helper->__('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('image_ids');
        $this->getMassactionBlock()
            ->addItem('allow', [
                'label' => Mage::helper('customerimages')->__('Allow'),
                'url' => $this->getUrl('*/*/massAllow'),
                'confirm' => Mage::helper('customerimages')->__('Are you sure?')
            ])
            ->addItem('disallow', [
                'label' => Mage::helper('customerimages')->__('Disallow'),
                'url' => $this->getUrl('*/*/massDisallow'),
                'confirm' => Mage::helper('customerimages')->__('Are you sure?')
            ]);
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }

}