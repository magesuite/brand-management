<?php
namespace MageSuite\BrandManagement\Model;

class BrandImages extends \Magento\Catalog\Model\AbstractModel implements \MageSuite\BrandManagement\Api\Data\BrandImagesInterface
{

    /**
     * @return string||null
     */
    public function getBase64EncodedData()
    {
        return $this->getData('base_64_encoded_data');
    }

    /**
     * @param string||null $data
     * @return $this
     */
    public function setBase64EncodedData($data)
    {
        return $this->setData('base_64_encoded_data', $data);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * @param string $mimeType
     * @return $this
     */
    public function setType($mimeType)
    {
        return $this->setData('type', $mimeType);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData('name', $name);
    }

    /**
     * @return int
     */
    public function getIsEncoded()
    {
        return $this->getData('is_encoded');
    }

    /**
     * @param int $isEncoded
     * @return $this
     */
    public function setIsEncoded($isEncoded)
    {
        return $this->setData('is_encoded', $isEncoded);
    }
}
