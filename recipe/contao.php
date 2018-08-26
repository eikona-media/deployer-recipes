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
