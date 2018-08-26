<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

// Contao shared dirs
set('shared_dirs', ['assets/images', 'files', 'templates', 'var/db_backups', 'var/logs', 'web/share']);

// Contao shared files - parameters are updated by tar_source
add('shared_files', ['system/config/localconfig.php']);

// Contao writable dirs
set('writable_dirs', []);

// Contao console bin
set('bin/console', '{{release_path}}/vendor/bin/contao-console');

/*
 * Contao Version Check
 */
desc('Contao version check');
task(
    'contao:version',
    function () {
        run('{{bin/php}} {{bin/console}} contao:version {{console_options}}');
    }
);

before('deploy:symlink', 'contao:version');

/*
 * Backup database
 * Requires non contao-core package `bwein-net/contao-database-backup` to be installed
 */
add('shared_dirs', ['var/db_backups']);

desc('Backup database');
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
before('deploy:symlink', 'contao:database:backup');

/*
 * Deploy via gitlab-ci
 */
set('gitlab_ci_parameters_file', 'app/config/parameters.yml');

// Symfony exclude dirs for upload
add(
    'exclude_dirs',
    [
        'app/config/parameters.*',
        'tests',
        'var',
        'web/bundles',
        'web/*dev.php',
    ]
);

// Contao exclude dirs for upload
add(
    'exclude_dirs',
    [
        'web/assets',
        'web/files',
        'web/share',
        'web/system',
    ]
);
