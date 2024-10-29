<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_ImageUploader
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */
namespace Mavenbird\ImageUploader\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Magento\Framework\File\Mime;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ImageHelper extends AbstractHelper
{
    public const XML_PATH_VECTOR_EXTENSIONS = 'mavenbird_imageuploader/extensions/vector';
    public const XML_PATH_WEB_IMAGE_EXTENSIONS = 'mavenbird_imageuploader/extensions/web_image';

    /**
     * @var File
     */
    protected $file;

    /**
     * @var FileDriver
     */
    protected $fileDriver;

    /**
     * @var Mime
     */
    protected $mime;

     /**
      * @var ScopeConfigInterface
      */
    protected $scopeConfig;
    /**
     * @param File $file
     * @param FileDriver $fileDriver
     * @param Mime $mime
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        File $file,
        FileDriver $fileDriver,
        Mime $mime,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->file = $file;
        $this->fileDriver = $fileDriver;
        $this->mime = $mime;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if the file is a vector image
     *
     * @param string $file
     *
     * @return bool
     */
    public function isVectorImage($file)
    {
        $fileInfo = $this->file->getPathInfo($file);
        $extension = strtolower($fileInfo['extension'] ?? '');
    
        if (empty($extension) && $this->fileDriver->isExists($file)) {
            $mimeType = $this->mime->getMimeType($file);
            $extension = str_replace('image/', '', $mimeType);
        }

        return in_array($extension, $this->getVectorExtensions());
    }

    /**
     * Get vector image extensions
     *
     * @return array
     */
    public function getVectorExtensions()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_VECTOR_EXTENSIONS, 'store') ?: [];
    }

    /**
     * Get web image extensions
     *
     * @return array
     */
    public function getWebImageExtensions()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_WEB_IMAGE_EXTENSIONS, 'store') ?: [];
    }
}
