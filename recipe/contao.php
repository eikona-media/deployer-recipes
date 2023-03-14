<?php
declare(strict_types=1);

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

use Deployer\Exception\RunException;

require_once __DIR__.'/deploy/cache.php';
require_once __DIR__.'/deploy/cleanup.php';
require_once __DIR__.'/deploy/maintenance.php';
require_once __DIR__.'/deploy/stage_specific_files.php';
require_once __DIR__.'/deploy/update_shared.php';
require_once __DIR__.'/build/composer.php';

/*
 * Contao Configuration
 */
set('contao_webroot', 'web');

// Contao shared dirs
set('shared_dirs', ['assets/images', 'files', 'system/config', 'templates', 'var/logs', '{{contao_webroot}}/share']);

// Contao writable dirs
set('writable_dirs', []);

// Contao console bin
set('bin/console', '{{release_path}}/vendor/bin/contao-console');

/*
 * Contao update shared dirs from repo
 */
set('update_shared_dirs', ['files', 'templates']);

// optionally add to deploy.php:
//before('deploy:shared', 'deploy:update_shared_dirs');

// optionally add to deploy.php:
//set('clear_shared_dirs', []);
//before('deploy:update_shared_dirs', 'deploy:clear_shared_dirs');

/*
 * Contao stage specific files
 */
set('stage_specific_files', [
    [
        'source' => '.env.ci_{{stage}}',
        'target' => '.env',
        'delete' => '.env.*'
    ]
]);
// optionally add to deploy.php:
//before('deploy:shared', 'deploy:stage_specific_files');

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

// Run Contao migrations and database update
desc('Run Contao migrations ');
task(
    'contao:migrate',
    function () {
        run('{{bin/php}} {{bin/console}} contao:migrate {{console_options}}');
    }
);

// optionally add to deploy.php:
//add('shared_dirs', ['var/backups']);
//before('deploy:symlink', 'contao:migrate');

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
        './{{contao_webroot}}/bundles',
        './{{contao_webroot}}/*dev.php',
    ]
);

// Contao exclude paths for upload
add(
    'exclude_paths',
    [
        './{{contao_webroot}}/assets',
        './{{contao_webroot}}/files',
        './{{contao_webroot}}/share',
        './{{contao_webroot}}/system',
    ]
);

/*
 * Contao build
 */
desc('Build your project');
task(
    'build',
    [
        'build:composer',
    ]
);

// optionally add to deploy.php:
//after('deploy:symlink', 'deploy:cache_status_clear');
//set('opcache_webroot', 'web');
//set('public_url', 'https://yourcontao.com');
//after('deploy:symlink', 'deploy:opcache_reset');

// optionally add to deploy.php:
//before('deploy:clear_shared_dirs', 'maintenance:enable:previous_release');
// or
//before('deploy:update_shared_dirs', 'maintenance:enable:previous_release');
// or
//before('contao:migrate', 'maintenance:enable:previous_release');

//after('deploy:vendors', 'maintenance:enable');
//after('deploy:symlink', 'maintenance:disable');

// optionally add to deploy.php:
//set('cleanup_previous_release_dirs', ['var/cache']);
//before('cleanup', 'cleanup:previous_release');
