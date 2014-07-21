<?php

namespace Oro\Bundle\AttachmentBundle\Placeholder;

use Doctrine\Common\Util\ClassUtils;

use Oro\Bundle\AttachmentBundle\EntityConfig\AttachmentScope;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;

class PlaceholderFilter
{
    /**
     * @var ConfigProvider
     */
    protected $attachmentConfigProvider;

    /**
     * @var ConfigProvider
     */
    protected $entityConfigProvider;

    /**
     * @param ConfigProvider $attachmentConfigProvider
     * @param ConfigProvider $entityConfigProvider
     */
    public function __construct(ConfigProvider $attachmentConfigProvider, ConfigProvider $entityConfigProvider)
    {
        $this->attachmentConfigProvider = $attachmentConfigProvider;
        $this->entityConfigProvider = $entityConfigProvider;
    }

    /**
     * Checks if the entity can has notes
     *
     * @param object $entity
     * @return bool
     */
    public function isAttachmentAssociationEnabled($entity)
    {
        if (null === $entity || !is_object($entity)) {
            return false;
        }

        $className = ClassUtils::getClass($entity);

        return
            $this->attachmentConfigProvider->hasConfig($className)
            && $this->attachmentConfigProvider->getConfig($className)->is('enabled')
            && $this->entityConfigProvider->hasConfig(
                AttachmentScope::ATTACHMENT,
                ExtendHelper::buildAssociationName($className)
            );
    }
}
