<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/contao.php';

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
