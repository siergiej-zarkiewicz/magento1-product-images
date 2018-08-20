<?php

/**
 * CustomerImages product resource model
 *
 * @category    Sz
 * @package     Sz_CustomerImages
 * @author      Sergey Zarkevich <sergey.zarkevich@gmail.com>
 */
class Sz_CustomerImages_Model_Resource_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    public function _construct()
    {
        $this->_init('customerimages/product', 'id');
    }

    /**
     * @param array $productIds
     * @param array $imageId
     * @return int
     */
    public function deleteByCondition(array $productIds, array $imageId)
    {
        $table = $this->getMainTable();
        $where[] = $this->_getWriteAdapter()->quoteInto('product_id IN (?)', $productIds);
        $where[] = $this->_getWriteAdapter()->quoteInto('image_id IN (?)', $imageId);
        $result = $this->_getWriteAdapter()->delete($table, $where);

        return $result;
    }
}
