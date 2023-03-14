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

require_once __DIR__.'/deploy/cache.php';
require_once __DIR__.'/deploy/cleanup.php';
require_once __DIR__.'/deploy/stage_specific_files.php';
require_once __DIR__.'/deploy/update_shared.php';
require_once __DIR__.'/build/composer.php';

/*
 * Contao Configuration
 */
// optionally add to deploy.php:
//set('public_path', 'web');

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
        './{{public_path}}/bundles',
        './{{public_path}}/*dev.php',
    ]
);

// Contao exclude paths for upload
add(
    'exclude_paths',
    [
        './{{public_path}}/assets',
        './{{public_path}}/files',
        './{{public_path}}/share',
        './{{public_path}}/system',
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
//set('cleanup_previous_release_dirs', ['var/cache']);
//before('deploy:cleanup', 'cleanup:previous_release');
