<?php

/**
 * Class Sz_CustomerImages_Block_Product_View_Images
 */
class Sz_CustomerImages_Block_Product_View_Images extends Mage_Uploader_Block_Single
{
    /**
     * @var
     */
    protected $_product;
    /**
     * @var
     */
    protected $_images;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::registry('product');
        }
        return $this->_product;
    }

    /**
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('customerimages/image/upload');
    }

    /**
     * @return Varien_Data_Collection
     */
    public function getGalleryImages()
    {
        /**
         * @var Sz_CustomerImages_Model_Resource_Image_Collection $collection
         */
        if(!$this->_images) {
            $collection = Mage::getResourceModel('customerimages/image_collection');
            $collection
                ->loadByProductId($this->getProduct()->getId())
                ->getVisible();

            $images = new Varien_Data_Collection();
            foreach ($collection as $item) {
                $image['url'] = $item->getUrl();
                $image['id'] = $item->getId();
                $image['path'] = $item->getImagePath();
                $image['file'] = $item->getFile();
                $images->addItem(new Varien_Object($image));
            }
            $this->_images = $images;
        }

        return $this->_images;
    }
}
