<?php

/**
 * Product model
 *
 * @category    Sz
 * @package     Sz_CustomerImages
 * @author      Sergey Zarkevich <sergey.zarkevich@gmail.com>
 */
class Sz_CustomerImages_Model_Product extends Mage_Core_Model_Abstract
{

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    /**
     * Initialize product model
     */
    public function _construct()
    {
        $this->_init('customerimages/product');
    }

    /**
     * @param array $productIds
     * @param array $imageIds
     * @return mixed
     */
    public function deleteByImage(array $productIds, array $imageIds)
    {
        return $this->getResource()->deleteByCondition($productIds, $imageIds);
    }

    /**
     * @param $newIds
     * @param $productId
     * @return $this
     */
    public function updateImage($newIds, $productId)
    {
        /** @var Sz_CustomerImages_Model_Resource_Product_Collection $productCollection */
        $currentIds = $this->getCollection()
            ->loadByProductId($productId)
            ->getColumnValues('image_id');

        $idsToDelete = array_diff($currentIds, $newIds);
        if ($idsToDelete) {
            $this->deleteByImage([$productId], $idsToDelete);
        }

        $idsToAdd = array_diff($newIds, array_intersect($newIds, $currentIds));

        foreach ($idsToAdd as $imageId) {
            $imageProduct = Mage::getModel('customerimages/product');
            $imageProduct->setProductId($productId);
            $imageProduct->setImageId($imageId);
            $imageProduct->save();
        }

        return $this;
    }

}
