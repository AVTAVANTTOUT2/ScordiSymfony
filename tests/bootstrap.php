<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

if (($_SERVER['APP_ENV'] ?? 'dev') === 'test') {
    $filesystem = new Filesystem();
    $databaseFile = dirname(__DIR__).'/var/data_test.db';

    if ($filesystem->exists($databaseFile)) {
        $filesystem->remove($databaseFile);
    }
}
