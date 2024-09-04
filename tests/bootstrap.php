<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

// executes the "php bin/console doctrine:database:create --if-not-exists --env=test --quiet" command
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:database:create --if-not-exists --env=test --quiet',
        $_ENV['APP_ENV'],
        __DIR__
    )
);

// executes the "php bin/console doctrine:migrations:migrate --no-interaction --env=test --quiet" command
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:migrations:migrate --no-interaction --env=test --quiet',
        $_ENV['APP_ENV'],
        __DIR__
    )
);
