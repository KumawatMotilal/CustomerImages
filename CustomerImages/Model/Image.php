<?php
namespace Kumawat\CustomerImages\Model;

use Magento\Framework\Model\AbstractModel;

class Image extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Kumawat\CustomerImages\Model\ResourceModel\Image::class);
    }
}

