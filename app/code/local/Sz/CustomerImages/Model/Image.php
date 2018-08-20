<?php

/**
 * Image model
 *
 * @category    Sz
 * @package     Sz_CustomerImages
 * @author      Sergey Zarkevich <sergey.zarkevich@gmail.com>
 */
class Sz_CustomerImages_Model_Image extends Mage_Core_Model_Abstract
{
    const STATUS_ALLOW = 1;
    const STATUS_DISALLOW = 0;

    /**
     * Initialize image model
     */
    public function _construct()
    {
        $this->_init('customerimages/image');
    }

    /**
     * @param array $newIds
     * @return $this
     */
    public function updateProduct(array $newIds)
    {
        /** @var Sz_CustomerImages_Model_Resource_Product_Collection $productCollection */
        $productCollection = Mage::getModel('customerimages/product')->getCollection();
        $currentIds = $productCollection
            ->loadByImageId($this->getId())
            ->getColumnValues('product_id');

        $idsToDelete = array_diff($currentIds, $newIds);

        if ($idsToDelete) {
            Mage::getModel('customerimages/product')->deleteByImage($idsToDelete, [$this->getId()]);
        }

        $idsToAdd = array_diff($newIds, array_intersect($newIds, $currentIds));
        foreach ($idsToAdd as $id) {
            $imageProduct = Mage::getModel('customerimages/product');
            $imageProduct->setProductId($id);
            $imageProduct->setImageId($this->getId());
            $imageProduct->save();
        }

        return $this;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return $this
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $imageProduct = Mage::getModel('customerimages/product');
        $imageProduct->setImageId($this->getId());
        $imageProduct->setProductId($product->getId());
        $imageProduct->save();
        return $this;
    }

    /**
     * @return Sz_CustomerImages_Model_Resource_Product_Collection
     */
    public function getProducts()
    {
        /** @var Sz_CustomerImages_Model_Resource_Product_Collection $collection */
        $collection = Mage::getModel('customerimages/product')->getCollection();
        $collection->loadByImageId($this->getId());
        return $collection;
    }

    /**
     * @return mixed
     */
    public function allow()
    {
        return $this->setAllow(self::STATUS_ALLOW)->save();
    }

    /**
     * @return mixed
     */
    public function disallow()
    {
        return $this->setAllow(self::STATUS_DISALLOW)->save();
    }

    /**
     * @return string
     */
    public function getBaseImageUrl()
    {
        return Mage::getBaseUrl('media') . 'customer_images';
    }

    /**
     * @param $file
     * @return string
     */
    public function getImageUrl($file)
    {
        $file = $this->_prepareFileForUrl($file);

        if (substr($file, 0, 1) == '/') {
            return $this->getBaseImageUrl() . $file;
        }

        return $this->getBaseImageUrl() . '/' . $file;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $file = $this->_prepareFileForUrl($this->getFile());

        if (substr($file, 0, 1) == '/') {
            return $this->getBaseImageUrl() . $file;
        }

        return $this->getBaseImageUrl() . '/' . $file;
    }

    /**
     * @param $file
     * @return mixed
     */
    protected function _prepareFileForUrl($file)
    {
        return str_replace(DS, '/', $file);
    }

    /**
     * @return string
     */
    public function getBaseImagePath()
    {
        return Mage::getBaseDir('media') . DS . 'customer_images';
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        $file = $this->_prepareFileForPath($this->getFile());

        if (substr($file, 0, 1) == DS) {
            return $this->getBaseImagePath() . DS . substr($file, 1);
        }

        return $this->getBaseImagePath() . DS . $file;
    }

    /**
     * @param $file
     * @return mixed
     */
    protected function _prepareFileForPath($file)
    {
        return str_replace('/', DS, $file);
    }
}
