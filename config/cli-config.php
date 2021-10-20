<?php

$container = require __DIR__ . '/container.php';

$entityManager = $container->get(\Doctrine\ORM\EntityManager::class);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
