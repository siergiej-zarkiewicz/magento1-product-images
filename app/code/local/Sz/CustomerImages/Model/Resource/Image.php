<?php

/**
 * CustomerImages image resource model
 *
 * @category    Sz
 * @package     Sz_CustomerImages
 * @author      Sergey Zarkevich <sergey.zarkevich@gmail.com>
 */
class Sz_CustomerImages_Model_Resource_Image extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    public function _construct()
    {
        $this->_init('customerimages/image', 'id');
    }
}
