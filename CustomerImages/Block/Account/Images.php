<?php
namespace Kumawat\CustomerImages\Block\Account;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;
use Kumawat\CustomerImages\Model\ResourceModel\Image\CollectionFactory;

class Images extends Template
{
    protected $customerSession;
    protected $imageCollectionFactory;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        CollectionFactory $imageCollectionFactory,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->imageCollectionFactory = $imageCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getCustomerImages()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomerId();
            $collection = $this->imageCollectionFactory->create();
            $collection->addFieldToFilter('customer_id', $customerId);
            return $collection;
        }
        return [];
    }
}
