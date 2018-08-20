<?php

/**
 * CustomerImages product collection model
 *
 * @category    Sz
 * @package     Sz_CustomerImages
 * @author      Sergey Zarkevich <sergey.zarkevich@gmail.com>
 */
class Sz_CustomerImages_Model_Resource_Product_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Collection initialization
     */
    public function _construct()
    {
        $this->_init('customerimages/product');
    }

    /**
     * @param $imageId
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function loadByImageId($imageId)
    {
        return $this->addFieldToFilter('image_id', $imageId);
    }

    /**
     * @param $productId
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function loadByProductId($productId)
    {
        return $this->addFieldToFilter('product_id', $productId);
    }
}
