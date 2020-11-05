<?php

namespace MageSuite\BrandManagement\Validator;

use Magento\Framework\Api\Data\ImageContentInterface;
use Magento\Framework\Api\ImageContentValidator as Validator;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Phrase;

/**
 * Class for Image content validation
 */
class ImageContentValidator extends Validator
{
    /**
     * @var array
     */
    private $defaultMimeTypes = [
        'image/jpg',
        'image/jpeg',
        'image/gif',
        'image/png',
        'image/svg+xml',
        'image/svg',
    ];

    /**
     * @var array
     */
    private $allowedMimeTypes;

    /**
     * @param  array  $allowedMimeTypes
     */
    public function __construct(
        array $allowedMimeTypes = []
    ) {
        $this->allowedMimeTypes = array_merge($this->defaultMimeTypes, $allowedMimeTypes);
        parent::__construct($this->allowedMimeTypes);
    }

    /**
     * Check if gallery entry content is valid
     *
     * @param  ImageContentInterface  $imageContent
     * @return bool
     * @throws InputException
     */
    public function isValid(ImageContentInterface $imageContent)
    {
        $fileContent = @base64_decode($imageContent->getBase64EncodedData(), true);
        if (empty($fileContent)) {
            throw new InputException(new Phrase('The image content must be valid base64 encoded data.'));
        }

        if (!$this->isSvg($fileContent)) {
            $imageProperties = @getimagesizefromstring($fileContent);
            if (empty($imageProperties)) {
                throw new InputException(new Phrase('The image content must be valid base64 encoded data.'));
            }
            $sourceMimeType = $imageProperties['mime'];
        } else {
            $sourceMimeType = $imageContent->getType();
        }

        if ($sourceMimeType != $imageContent->getType() || !$this->isMimeTypeValid($sourceMimeType)) {
            throw new InputException(new Phrase('The image MIME type is not valid or not supported.'));
        }

        if (!$this->isNameValid($imageContent->getName())) {
            throw new InputException(new Phrase('Provided image name contains forbidden characters.'));
        }

        return true;
    }

    /**
     * Check if file content is svg
     *
     * @param  string  $fileContent
     * @return bool
     */
    public function isSvg($fileContent)
    {
        // TODO make it more elegant
        $temp = tempnam(sys_get_temp_dir(), 'TMP_');
        file_put_contents($temp, $fileContent);

        $mt = mime_content_type($temp);
        return 'image/svg+xml' === $mt || 'image/svg' === $mt;
    }

}
