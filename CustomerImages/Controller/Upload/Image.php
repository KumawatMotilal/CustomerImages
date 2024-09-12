<?php
namespace Kumawat\CustomerImages\Controller\Upload;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Kumawat\CustomerImages\Model\ImageFactory;
use Magento\Framework\App\Filesystem\DirectoryList as DirectoryListData;

class Image extends Action
{
    protected $resultJsonFactory;
    protected $customerSession;
    protected $uploaderFactory;
    protected $directoryList;
    protected $imageFactory;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Session $customerSession,
        UploaderFactory $uploaderFactory,
        DirectoryList $directoryList,
        ImageFactory $imageFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
        $this->uploaderFactory = $uploaderFactory;
        $this->directoryList = $directoryList;
        $this->imageFactory = $imageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        try {
            if (!$this->customerSession->isLoggedIn()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('You must be logged in to upload images.'));
            }

            $customerId = $this->customerSession->getCustomerId();
            $uploader = $this->uploaderFactory->create(['fileId' => 'customer_image']);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            $mediaDirectory = $this->directoryList->getPath(DirectoryListData::MEDIA);
            $result = $uploader->save($mediaDirectory . '/customer/images');

            if (!$result) {
                throw new \Magento\Framework\Exception\LocalizedException(__('File upload failed.'));
            }

            // Save the image path to the database
            $imagePath = 'customer/images/' . $result['file'];
            $this->saveImagePathToDatabase($customerId, $imagePath);

            return $result->setData([
                'success' => true,
                'message' => __('Image uploaded successfully.'),
                'image_url' => $this->getImageUrl($imagePath)
            ]);
            
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    protected function saveImagePathToDatabase($customerId, $imagePath)
    {
        $image = $this->imageFactory->create();
        $image->setData('customer_id', $customerId);
        $image->setData('image_path', $imagePath);
        $image->save();
    }    

    protected function getImageUrl($imagePath)
    {
        return $this->_url->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $imagePath;
    }
}
