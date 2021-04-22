<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/deploy/cache.php';
require_once __DIR__.'/deploy/update_shared.php';
require_once __DIR__.'/build/composer.php';

/*
 * Shopware Configuration
 */

// Shopware shared files and dirs
set('shared_files', ['.env']);
set('shared_dirs', [
    'config/jwt',
    'custom/plugins',
    'files',
    'public/media',
    'public/sitemap',
    'public/thumbnail',
    'var/log',
]);

// Shopware writeable dirs
set('writable_dirs', [
    'config/jwt',
    'custom/plugins',
    'files',
    'public/bundles',
    'public/css',
    'public/fonts',
    'public/js',
    'public/media',
    'public/sitemap',
    'public/theme',
    'public/thumbnail',
    'var',
]);

/*
 * Shopware update shared dirs + parameters from repo
 */
set('update_shared_dirs', ['custom/plugins', 'files']);
set('update_shared_parameters_target', '.env');
set('update_shared_parameters_source', '.env.ci_{{stage}}');
set('update_shared_parameters_delete', '.env.*');

// optionally add to deploy.php:
//before('deploy:shared', 'deploy:update_shared_dirs');
//after('deploy:shared', 'deploy:update_shared_parameters');

/*
 * Generate JWT Secret, if it does not exist
 */
desc('Generate JWT Secret');
task(
    'deploy:generate-jwt-secret',
    function () {
        if (!test('[ -f {{release_path}}/config/jwt/private.pem ]')
            || !test('[ -f {{release_path}}/config/jwt/public.pem ]')) {
            run('{{bin/php}} {{bin/console}} system:generate-jwt-secret -f {{console_options}}');
        }
    }
);

/*
 * Shopware build
 */
desc('Build your project');
task('build', [
    'build:composer',
    // Dump autoload after `composer install --no-scripts`
    // see: https://github.com/shopware/production/issues/8
    'build:composer:dump-autoload',
]);

/*
 * Upload with tar
 */

// Symfony exclude paths for upload
add(
    'exclude_paths',
    [
        './public/bundles',
        './var/cache',
        './var/log',
    ]
);

// Shopware exclude paths for upload
add(
    'exclude_paths',
    [
        './public/css',
        './public/fonts',
        './public/js',
        './public/media',
        './public/sitemap',
        './public/theme',
        './public/thumbnail',
    ]
);

// Tasks
after('deploy:vendors', 'deploy:generate-jwt-secret');

// optionally add to deploy.php:
// Cache clear
//after('deploy:symlink', 'deploy:cache_status_clear');
//after('deploy:symlink', 'deploy:cache_accelerator_clear');
