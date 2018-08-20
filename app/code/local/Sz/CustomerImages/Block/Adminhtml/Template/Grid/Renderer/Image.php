<?php

/**
 * Class Sz_CustomerImages_Block_Adminhtml_Template_Grid_Renderer_Image
 */
class Sz_CustomerImages_Block_Adminhtml_Template_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return mixed|string
     */
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }

    /**
     * @param Varien_Object $row
     * @return string
     */
    protected function _getValue(Varien_Object $row)
    {
        $file = $row->getData($this->getColumn()->getIndex());
        $url = Mage::getSingleton('customerimages/image')->getImageUrl($file);
        return "<img src='{$url}' width='60px'/>";
    }
}