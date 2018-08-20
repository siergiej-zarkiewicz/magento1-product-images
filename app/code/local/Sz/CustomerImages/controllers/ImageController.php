<?php

/**
 * Product controller
 *
 * @category   Mage
 * @package    Mage_Catalog
 */
class Sz_CustomerImages_ImageController extends Mage_Core_Controller_Front_Action
{
    /**
     * @return bool|void
     */
    public function uploadAction()
    {
        try {
            $productId = (int) $this->getRequest()->getParam('product_id');
            $product = Mage::getModel('catalog/product')->load($productId);
            if (!$product->getId()) {
                return false;
            }

            $uploader = new Mage_Core_Model_File_Uploader('image');

            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));

            $uploader->addValidateCallback('catalog_product_image',
                Mage::helper('catalog/image'), 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->addValidateCallback(
                Mage_Core_Model_File_Validator_Image::NAME,
                Mage::getModel('core/file_validator_image'),
                'validate'
            );

            $result = $uploader->save(
                Mage::getSingleton('customerimages/image')->getBaseImagePath()
            );

            /**
             * @var Sz_CustomerImages_Model_Image $image
             */
            $image = Mage::getModel('customerimages/image');
            $image->setFile($result['file'])->save();

            $image->setProduct($product);

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirectReferer();
            return false;
        }

        Mage::getSingleton('core/session')->addSuccess('Image was uploaded and waiting for admin accept.');
        $this->_redirectReferer();
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _getSession()
    {
        return Mage::getSingleton('catalog/session');
    }
}
