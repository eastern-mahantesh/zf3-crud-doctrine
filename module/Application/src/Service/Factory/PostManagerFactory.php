<?php

declare(strict_types=1);

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\PostManager;

class PostManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): PostManager
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new PostManager($entityManager);
    }
}
