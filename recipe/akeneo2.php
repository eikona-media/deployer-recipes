<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__ . '/deploy/update_shared.php';

/*
 * Akeneo Configuration
 */

// Akeneo shared files and dirs
set('shared_files', ['app/config/parameters.yml', '.env']);
set('shared_dirs', [
    'app/archive',
    'app/file_storage',
    'app/uploads',
    'var/logs',
    'var/sessions',
    'var/backups',
]);

// Akeneo writeable dirs
set('writable_dirs', [
    'app/archive',
    'app/file_storage',
    'app/uploads',
    'bin/backup',
    'var/cache',
    'var/logs',
    'var/sessions',
    'web/media',
]);

// optionally add to deploy.php:
//before('deploy:shared', 'deploy:update_shared_dirs');
//after('deploy:shared', 'deploy:update_shared_parameters');

/*
 * Akeneo install assets
 */
desc('Execute pim:installer:assets');
task('pim:installer:assets', function () {
    run('{{bin/php}} {{bin/console}} pim:installer:assets --symlink --clean {{console_options}}');
});

/*
 * Akeneo dump required paths
 */
desc('Execute pim:installer:dump-require-paths');
task('pim:installer:dump-require-paths', function () {
    run('{{bin/php}} {{bin/console}} pim:installer:dump-require-paths');
});

/*
 * Akeneo webpack compile
 */
desc('Execute yarn:compile');
task('yarn:compile', function () {
    run('cd {{release_path}} && {{bin/yarn}} run webpack');
});

/**
 * Akeneo build
 */
set('local/bin/php', function () {
    return runLocally('which php');
});

set('local/bin/composer', function () {
    return runLocally('which composer');
});

set('local/bin/yarn', function () {
    return runLocally('which yarn');
});

task('build', function () {
    runLocally('{{local/bin/php}} {{local/bin/composer}} install --prefer-dist --optimize-autoloader --no-progress --no-scripts');
    runLocally('{{local/bin/yarn}} install');
})->desc('Build your project');

/*
 * Akeneo update shared dirs + parameters from repo
 */
set('update_shared_parameters', 'app/config/parameters.yml');

// Symfony exclude paths for upload
add(
    'exclude_paths',
    [
        './app/config/parameters.*',
        './tests',
        './var/cache',
        './var/logs',
        './web/bundles',
        './web/*dev.php',
    ]
);

// Akeneo exclude paths for upload
add(
    'exclude_paths',
    [
        './var/bootstrap.php.cache',
    ]
);

// Tasks
after('deploy:vendors', 'pim:installer:assets');
after('pim:installer:assets', 'pim:installer:dump-require-paths');
after('pim:installer:dump-require-paths', 'yarn:compile');
