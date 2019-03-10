<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

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
set('update_shared_parameters', 'app/config/parameters.yml');

// optionally add to deploy.php:
//before('deploy:shared', 'deploy:update_shared_dirs');
//after('deploy:shared', 'deploy:update_shared_parameters');

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
        try {
            run('{{bin/php}} {{bin/console}} contao:database:update {{console_options}}');
        } catch (\Exception $exception) {
            writeln('<comment>To update database setup "fuzzyma/contao-database-commands-bundle"</comment>');
        }
    }
);

// optionally add to deploy.php:
//before('deploy:symlink', 'contao:database:update');
// or
//after('contao:database:backup', 'contao:database:update');

/*
 * Upload with tar
 */

// Symfony exclude paths for upload
add(
        'exclude_paths',
        [
            './app/config/parameters.*',
            './tests',
            './var',
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
task('build', [
    'build:composer',
])->desc('Build your project');
