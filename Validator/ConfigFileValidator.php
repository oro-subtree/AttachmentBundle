<?php

namespace Oro\Bundle\AttachmentBundle\Validator;

use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Constraints\File as FileConstrain;

use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\AttachmentBundle\Entity\Attachment;
use Oro\Bundle\AttachmentBundle\EntityConfig\AttachmentScope;

use Oro\Bundle\ConfigBundle\Config\UserConfigManager;

use Oro\Bundle\EntityConfigBundle\Config\Id\FieldConfigId;
use Oro\Bundle\EntityConfigBundle\Config\Config;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;

class ConfigFileValidator
{
    /** @var Validator */
    protected $validator;

    /** @var UserConfigManager */
    protected $config;

    /** @var ConfigProvider */
    protected $attachmentConfigProvider;

    /**
     * @param Validator         $validator
     * @param ConfigManager     $configManager
     * @param UserConfigManager $config
     */
    public function __construct(Validator $validator, ConfigManager $configManager, UserConfigManager $config)
    {
        $this->validator                = $validator;
        $this->attachmentConfigProvider = $configManager->getProvider('attachment');
        $this->config                   = $config;
    }

    /**
     * @param string          $dataClass Parent entity class name
     * @param string          $fieldName Field name where new file/image field was added
     * @param File|Attachment $entity    File entity
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    public function validate($dataClass, $fieldName, $entity)
    {
        if ($dataClass == AttachmentScope::ATTACHMENT) {
            /**
             * TODO:
             *    may be we should store maxsize & mime types in global config ???
             */
            $fileSize    = 10 * 1024 * 1024;
            $configValue = 'upload_image_mime_types';
        } else {
            /** @var Config $attachmentFileConfig */
            $attachmentFileConfig = $this->attachmentConfigProvider->getConfig($dataClass, $fieldName);

            /** @var FieldConfigId $fieldConfigId */
            $fieldConfigId = $attachmentFileConfig->getId();
            if ($fieldConfigId->getFieldType() === 'file') {
                $configValue = 'upload_mime_types';
            } else {
                $configValue = 'upload_image_mime_types';
            }

            $fileSize = $attachmentFileConfig->get('maxsize') * 1024 * 1024;
        }

        $mimeTypes = explode("\n", $this->config->get('oro_attachment.' . $configValue));
        foreach ($mimeTypes as $id => $value) {
            $mimeTypes[$id] = trim($value);
        }

        return $this->validator->validateValue(
            $entity->getFile(),
            [
                new FileConstrain(
                    [
                        'maxSize'   => $fileSize,
                        'mimeTypes' => $mimeTypes
                    ]
                )
            ]
        );
    }
}
