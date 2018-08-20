<?php

/**
 * Class Sz_CustomerImages_Model_Observer
 */
class Sz_CustomerImages_Model_Observer
{
    /**
     * @var static bool
     */
    static protected $_singletonFlag = false;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
            $product = $observer->getEvent()->getProduct();

            /** @var Sz_CustomerImages_Model_Product $imageProduct */
            $imageProduct = Mage::getModel('customerimages/product');
            try {
                $imageIds = $this->_getRequest()->getPost('image_ids');
                if (isset($imageIds)) {

                    $imageIds = is_array($imageIds) ? array_filter($imageIds, 'is_numeric') : [];

                    $imageProduct->updateImage($imageIds, $product->getId());
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}