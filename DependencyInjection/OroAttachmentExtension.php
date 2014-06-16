<?php

namespace Oro\Bundle\AttachmentBundle\DependencyInjection;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OroAttachmentExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $yaml = new Parser();
        $value = $yaml->parse(file_get_contents(__DIR__.'/../Resources/config/files.yml'));
        $container->setParameter('oro_attachment.files', $value['file-icons']);
    }
}
