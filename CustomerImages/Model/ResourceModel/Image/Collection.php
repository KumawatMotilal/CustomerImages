<?php
namespace Kumawat\CustomerImages\Model\ResourceModel\Image;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Kumawat\CustomerImages\Model\Image', 'Kumawat\CustomerImages\Model\ResourceModel\Image');
    }
}
