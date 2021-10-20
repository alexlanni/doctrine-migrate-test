<?php

declare(strict_types=1);

namespace App;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'laminas-cli' => [
                'commands' => [
                    'migrate:up'    => Command\MigrateUpCommand::class,
                    'migrate:down'  => Command\MigrateDownCommand::class,
                ],
            ],
            'sp-schema-migrate-config' => [
                'version-history' => [
                    'test' => [
                        'v0',
                        'v1.0',
                        'v1.1',
                        'v1.2',
                        'v1.3',
                        'v2.0'
                    ],
                    'test2' => [
                        'v0',
                        'v1.0'
                    ],
                ],

            ]
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class    => Handler\HomePageHandlerFactory::class,
                Command\MigrateUpCommand::class   => Command\MigrateCommandFactory::class,
                Command\MigrateDownCommand::class => Command\MigrateCommandFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
