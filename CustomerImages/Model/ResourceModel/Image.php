<?php
namespace Kumawat\CustomerImages\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Image extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('customer_images', 'entity_id');
    }
}