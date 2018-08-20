<?php

/**
 * CustomerImages image collection model
 *
 * @category    Sz
 * @package     Sz_CustomerImages
 * @author      Sergey Zarkevich <sergey.zarkevich@gmail.com>
 */
class Sz_CustomerImages_Model_Resource_Image_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Collection initialization
     */
    public function _construct()
    {
        $this->_init('customerimages/image');
    }

    /**
     * @return $this
     */
    public function joinProduct()
    {
        $this->getSelect()
            ->joinLeft(
                ['image_product' => $this->getTable('customerimages/product')],
                '`image_product`.`image_id` = `main_table`.`id`'
            );
        return $this;
    }

    /**
     * @param $productId
     * @return $this
     */
    public function loadByProductId($productId)
    {
        return $this
            ->joinProduct()
            ->addFieldToFilter('product_id', $productId);
    }

    /**
     * @return $this
     */
    public function getVisible()
    {
        return $this
            ->addFieldToFilter('allow', Sz_CustomerImages_Model_Image::STATUS_ALLOW)
            ->addFieldToFilter('enable', Sz_CustomerImages_Model_Product::STATUS_ENABLE);
    }

    /**
     * @return $this
     */
    public function getAllow()
    {
        return $this->addFieldToFilter('allow', Sz_CustomerImages_Model_Image::STATUS_ALLOW);
    }
}
