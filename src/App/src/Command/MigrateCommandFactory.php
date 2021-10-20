<?php

namespace App\Command;

use App\Handler\HomePageHandler;
use Doctrine\ORM\EntityManager;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MigrateCommandFactory
{

    public function __invoke(ContainerInterface $container, $className)
    {
        $config = $container->get('config');

        $versionHistory = $config['sp-schema-migrate-config']['version-history'];

        $emMigration = $container->get(EntityManager::class);
        return new $className($versionHistory, $emMigration);
    }

}