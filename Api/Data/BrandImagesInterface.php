<?php
namespace MageSuite\BrandManagement\Api\Data;

interface BrandImagesInterface extends \Magento\Framework\Api\Data\ImageContentInterface
{
    /**
     * @return string
     */
    public function getBase64EncodedData();

    /**
     * @param string $data
     * @return $this
     */
    public function setBase64EncodedData($data);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $mimeType
     * @return $this
     */
    public function setType($mimeType);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);
}
