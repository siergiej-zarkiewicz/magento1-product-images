<?php

/**
 * Class Sz_CustomerImages_Helper_Data
 */
class Sz_CustomerImages_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return string
     */
    public function getBaseImagePath()
    {
        return Mage::getBaseDir('media') . DS . 'customer_images';
    }

}
	 