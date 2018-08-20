<?php

/**
 * Class Sz_CustomerImages_Adminhtml_ImageController
 */
class Sz_CustomerImages_Adminhtml_ImageController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        /** @var Sz_CustomerImages_Model_Image $image */
        $image = Mage::getModel('customerimages/image')->load($id);

        if ($image->getId()) {
            if ($this->getRequest()->isPost()) {

                try {
                    $data = $this->getRequest()->getPost();
                    $image->addData($data);
                    $image->setAllow(isset($data['allow']) ? $data['allow'] : 0);
                    $image->save();

                    if (isset($data['product_ids'])) {
                        $product_ids = is_array($data['product_ids']) ? array_filter($data['product_ids'], 'is_numeric') : [];
                        $image->updateProduct($product_ids);
                    }

                } catch (Exception $e) {
                    $this->_getSession()->addError(
                        Mage::helper('customerimages')->__('An error occurred while saving the image data. Please review the log and try again.')
                    );
                    Mage::logException($e);
                    $this->_redirect('*/*/edit', array('id' => $id));
                    return $this;
                }

                $this->_redirect('*/*/', array('id' => $id));

            } else {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $image
                        ->setData($data)
                        ->setId($id);
                }
            }

        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('awesome')->__('The image does not exist'));
            $this->_redirect('*/*/');
        }

        Mage::register('image_data', $image);
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * @return $this
     */
    public function newAction()
    {
        /** @var Sz_CustomerImages_Model_Image $image */

        if ($this->getRequest()->isPost() && $image = $this->uploadImage()) {
            try {
                $data = $this->getRequest()->getPost();

                $image->addData($data);
                $image->setAllow(isset($data['allow']) ? $data['allow'] : 0);
                $image->save();

                if (isset($data['product_ids'])) {
                    $product_ids = is_array($data['product_ids']) ? array_filter($data['product_ids'], 'is_numeric') : [];
                    $image->updateProduct($product_ids);
                }

            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('customerimages')->__('An error occurred while saving the image data. Please review the log and try again.')
                );
                Mage::logException($e);
                $this->_redirect('*/*/');
                return $this;
            }

            $this->_redirect('*/*/');

        } else {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if ($data) {
                $image->setData($data);
            }
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * @return $this
     */
    public function massAllowAction()
    {
        $imageIds = $this->getRequest()->getParam('image_ids');
        $helper = Mage::helper('customerimages');

        if (!is_array($imageIds)) {
            Mage::getSingleton('adminhtml/session')->addError($helper->__('Please select image.'));
        } else {
            try {

                /**
                 * @var Sz_CustomerImages_Model_Image $image
                 */
                $count = 0;
                foreach ($imageIds as $id) {
                    $image = Mage::getModel('customerimages/image')->load($id);
                    if ($image->getId()) {
                        $image->allow();
                        $count++;
                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $helper->__('Total of %d record(s) were allowed.', $count)
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
        $this->loadLayout();
        $this->renderLayout();
        return $this;
    }

    /**
     * @return $this
     */
    public function massDisallowAction()
    {
        $imageIds = $this->getRequest()->getParam('image_ids');
        $helper = Mage::helper('customerimages');

        if (!is_array($imageIds)) {
            Mage::getSingleton('adminhtml/session')->addError($helper->__('Please select image.'));
        } else {
            try {

                /**
                 * @var Sz_CustomerImages_Model_Image $image
                 */
                $count = 0;
                foreach ($imageIds as $id) {
                    $image = Mage::getModel('customerimages/image')->load($id);
                    if ($image->getId()) {
                        $image->disallow();
                        $count++;
                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $helper->__('Total of %d record(s) were disallowed.', $count)
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
        $this->loadLayout();
        $this->renderLayout();
        return $this;
    }

    /**
     *
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('customerimages/adminhtml_catalog_image_grid')->toHtml()
        );
    }

    /**
     *
     */
    public function gridEditAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('customerimages/adminhtml_catalog_image_edit_renderer_grid')->toHtml()
        );
    }

    /**
     *
     */
    public function gridNewAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('customerimages/adminhtml_catalog_image_new_renderer_grid')->toHtml()
        );
    }

    /**
     *
     */
    public function gridImageListAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('customerimages/adminhtml_catalog_product_edit_tab_image_list')->toHtml()
        );
    }

    /**
     *
     */
    public function exportImagesCsvAction()
    {
        $fileName = 'customer_images.csv';
        $grid = $this->getLayout()->createBlock('customerimages/adminhtml_catalog_image_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *
     */
    public function exportImagesExcelAction()
    {
        $fileName = 'customer_images.xml';
        $grid = $this->getLayout()->createBlock('customerimages/adminhtml_catalog_image_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    /**
     * @return false|Mage_Core_Model_Abstract|Sz_CustomerImages_Model_Image
     */
    public function uploadImage()
    {
        $image = Mage::getModel('customerimages/image');
        try {
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
            $image
                ->setFile($result['file'])
                ->save();

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return false;
        }

        return $image;
    }

    /**
     * Initialize product from request parameters
     *
     * @return Mage_Catalog_Model_Product
     * @throws \Mage_Core_Exception
     */
    protected function _initProduct()
    {
        $productId  = (int) $this->getRequest()->getParam('id');
        $product    = Mage::getModel('catalog/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($productId) {
            try {
                $product->load($productId);
            } catch (Exception $e) {
                $product->setTypeId(Mage_Catalog_Model_Product_Type::DEFAULT_TYPE);
                Mage::logException($e);
            }
        }

        Mage::register('current_product', $product);
        return $product;
    }

}