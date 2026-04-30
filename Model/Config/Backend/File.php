<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Magefan\Translation\Model\Config\Backend;

use Magefan\Translation\Model\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Config\Model\Config\Backend\File\RequestData\RequestDataInterface;
use Magento\Framework\App\RequestInterface;

class File extends \Magento\Config\Model\Config\Backend\File
{
    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param UploaderFactory $uploaderFactory
     * @param RequestDataInterface $requestData
     * @param Filesystem $filesystem
     * @param WriterInterface $configWriter
     * @param EncryptorInterface $encryptor
     * @param RequestInterface $request
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context              $context,
        Registry             $registry,
        ScopeConfigInterface $config,
        TypeListInterface    $cacheTypeList,
        UploaderFactory      $uploaderFactory,
        RequestDataInterface $requestData,
        Filesystem           $filesystem,
        WriterInterface      $configWriter,
        EncryptorInterface   $encryptor,
        RequestInterface $request,
        ?AbstractResource     $resource = null,
        ?AbstractDb           $resourceCollection = null,
        array                $data = []
    ) {
        $this->configWriter = $configWriter;
        $this->encryptor = $encryptor;
        $this->request = $request;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $uploaderFactory,
            $requestData,
            $filesystem,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @return void
     */
    public function beforeSave()
    {
        try {
            $values = $this->getValue();

            if (!empty($values['delete'])) {
                $encryptedContent = null;
                $this->configWriter->delete(Config::XML_PATH_GOOGLE_V3_CONFIG_FILE_NAME, $this->getScope(), $this->getScopeId());
            } else {
                if ($values['type'] === 'application/json') {
                    $jsonContent = file_get_contents($values['tmp_name']);
                    $encryptedContent = $this->encryptor->encrypt($jsonContent);
                }
            }

            $this->configWriter->save(Config::XML_PATH_GOOGLE_V3_AUTH_DATA, $encryptedContent, $this->getScope(), $this->getScopeId());
            unlink($values['tmp_name']);
        } catch (\Exception $e) {
            /**/
        }
    }
}
