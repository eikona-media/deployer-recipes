<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

use Deployer\Exception\RuntimeException;

require_once __DIR__.'/deploy/cache.php';
require_once __DIR__.'/deploy/cleanup.php';
require_once __DIR__.'/deploy/maintenance.php';
require_once __DIR__.'/deploy/update_shared.php';
require_once __DIR__.'/build/composer.php';

/*
 * Contao Configuration
 */

// Contao shared dirs
set('shared_dirs', ['assets/images', 'files', 'system/config', 'templates', 'var/logs', 'web/share']);

// Contao writable dirs
set('writable_dirs', []);

// Contao console bin
set('bin/console', '{{release_path}}/vendor/bin/contao-console');

/*
 * Contao update shared dirs + parameters from repo
 */
set('update_shared_dirs', ['files', 'templates']);
set('update_shared_parameters_target', 'app/config/parameters.yml');

// optionally add to deploy.php:
//before('deploy:shared', 'deploy:update_shared_dirs');
//after('deploy:shared', 'deploy:update_shared_parameters');

// optionally add to deploy.php:
//set('clear_shared_dirs', []);
//before('deploy:update_shared_dirs', 'deploy:clear_shared_dirs');

/*
 * Contao version integrity check
 */
desc('Contao version integrity check');
task(
    'contao:version',
    function () {
        run('{{bin/php}} {{bin/console}} contao:version {{console_options}}');
    }
);
before('deploy:symlink', 'contao:version');

/*
 * Backup contao database
 * Requires non contao-core package `bwein-net/contao-database-backup` to be installed
 */
desc('Backup contao database');
task(
    'contao:database:backup',
    function () {
        try {
            run('{{bin/php}} {{bin/console}} bwein:database:backup deploy {{console_options}}');
        } catch (\Exception $exception) {
            writeln('<comment>To backup database setup "bwein-net/contao-database-backup"</comment>');
        }
    }
);

// optionally add to deploy.php:
//add('shared_dirs', ['var/db_backups']);
//before('deploy:symlink', 'contao:database:backup');

/*
 * Update database
 * Requires non contao-core package `fuzzyma/contao-database-commands-bundle` to be installed
 */
desc('Update contao database');
task(
    'contao:database:update',
    function () {
        // First try native update command (Contao >= 4.9)
        try {
            if (version_compare(run('{{bin/php}} {{bin/console}} contao:version'), '4.9.0', '>=')) {
                run('{{bin/php}} {{bin/console}} contao:migrate --schema-only {{console_options}}');

                writeln('<comment>Please use the new contao:migrate task in your deploy.php!</comment>');

                return;
            }
        } catch (RuntimeException $e) {
        }

        // Then try command provided by contao-database-commands-bundle
        try {
            run('cd {{release_path}} && {{bin/composer}} show fuzzyma/contao-database-commands-bundle');
        } catch (RuntimeException $e) {
            writeln('<comment>To update database setup "fuzzyma/contao-database-commands-bundle"</comment>');
            return;
        }

        run('{{bin/php}} {{bin/console}} contao:database:update {{console_options}}');
    }
);

// Run Contao migrations and database update
task(
    'contao:migrate',
    function () {
        run('{{bin/php}} {{bin/console}} contao:migrate {{console_options}}');
    }
)->desc('Run Contao migrations ');

// optionally add to deploy.php:
//before('deploy:symlink', 'contao:migrate');
// or
//after('contao:database:backup', 'contao:migrate');

/*
 * Upload with tar
 */

// Symfony exclude paths for upload
add(
    'exclude_paths',
    [
        './app/config/parameters.*',
        './config/parameters.*',
        './tests',
        './var',
        '/app/Resources/contao/config/runonce*',
        './web/bundles',
        './web/*dev.php',
    ]
);

// Contao exclude paths for upload
add(
    'exclude_paths',
    [
        './web/assets',
        './web/files',
        './web/share',
        './web/system',
    ]
);

/*
 * Contao build
 */
task(
    'build',
    [
        'build:composer',
    ]
)->desc('Build your project');

// optionally add to deploy.php:
//after('deploy:symlink', 'deploy:cache_status_clear');
//after('deploy:symlink', 'deploy:cache_accelerator_clear');

// optionally add to deploy.php:
//after('deploy:vendors', 'maintenance:enable');
//after('deploy:symlink', 'maintenance:disable');

// optionally add to deploy.php:
//set('cleanup_previous_release_dirs', ['var/cache']);
//before('cleanup', 'cleanup:previous:release');
