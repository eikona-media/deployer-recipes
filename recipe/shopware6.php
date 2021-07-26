<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__ . '/deploy/cache.php';
require_once __DIR__ . '/deploy/update_shared.php';
require_once __DIR__ . '/build/composer.php';

/*
 * Shopware Configuration
 */

// Shopware shared files and dirs
set('shared_files', ['.env']);
set(
    'shared_dirs',
    [
        'config/jwt',
        'custom/plugins',
        'files',
        'public/media',
        'public/sitemap',
        'public/thumbnail',
        'var/log',
    ]
);

// Shopware writeable dirs
set(
    'writable_dirs',
    [
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
    ]
);

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
 * Shopware build
 */
desc('Build shopware recovery');
task(
    'shopware:build:recovery',
    static function () {
        runLocally(
            'cd {{build_composer_path}} && {{local/bin/php}} {{local/bin/composer}} {{build_composer_options}} {{build_composer_options_extra}} --working-dir vendor/shopware/recovery',
            get('build_composer_run_options')
        );
        runLocally(
            'cd {{build_composer_path}} && {{local/bin/php}} {{local/bin/composer}} {{build_composer_options}} {{build_composer_options_extra}} --working-dir=vendor/shopware/recovery/Common',
            get('build_composer_run_options')
        );
    }
);

set('build_shopware_js_run_options', ['env' => ['CI' => true], 'timeout' => null]);
desc('Build shopware Javascript');
task(
    'shopware:build:js',
    static function () {
        runLocally('cd ./ && bash bin/build-js.sh', get('build_shopware_js_run_options'));
    }
);

desc('Build your project');
task(
    'build',
    [
        'build:composer',
        'shopware:build:recovery',
        'shopware:build:js',
    ]
);

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

/*
 * Touch shopware install.lock
 */
desc('Touch shopware install.lock');
task(
    'shopware:install:lock',
    static function () {
        run('touch {{release_path}}/install.lock');
    }
);
before('deploy:vendors', 'shopware:install:lock');

/*
 * Dump bundle config
 */
desc('Dump bundle config');
task(
    'shopware:bundle:dump',
    function () {
        run('{{bin/php}} {{bin/console}} bundle:dump {{console_options}}');
    }
);
before('deploy:vendors', 'shopware:bundle:dump');

/*
 * Generate JWT Secret, if it does not exist
 */
desc('Generate JWT Secret');
task(
    'shopware:generate-jwt-secret',
    function () {
        if (!test('[ -f {{release_path}}/config/jwt/private.pem ]')
            || !test('[ -f {{release_path}}/config/jwt/public.pem ]')) {
            run('{{bin/php}} {{bin/console}} system:generate-jwt-secret -f {{console_options}}');
        }
    }
);
after('deploy:vendors', 'shopware:generate-jwt-secret');

/*
 * Install assets
 */
desc('Install assets');
task(
    'shopware:asset:install',
    function () {
        run('{{bin/php}} {{bin/console}} asset:install {{console_options}}');
    }
);
after('deploy:vendors','shopware:asset:install');

/*
 * Compile shopware theme
 */
desc('Compile shopware theme');
task(
    'shopware:compile:theme',
    static function () {
        run('{{bin/php}} {{bin/console}} theme:compile {{console_options}}');
    }
);
before('deploy:cache:clear', 'shopware:compile:theme');

/*
 * Migrate shopware database
 */
desc('Migrate shopware database');
task(
    'shopware:migrate',
    static function () {
        run('{{bin/php}} {{bin/console}} database:migrate --all {{console_options}}');
    }
);
//before('shopware:compile:theme', 'shopware:migrate');

/*
 * Warmup shopware http cache
 */
desc('Warmup shopware http cache');
task(
    'shopware:cache:warmup',
    static function () {
        run('{{bin/php}} {{bin/console}} http:cache:warm:up {{console_options}}');
    }
);
after('deploy:cache:warmup', 'shopware:cache:warmup');

// optionally add to deploy.php:
// Cache clear
//after('deploy:symlink', 'deploy:cache_status_clear');
//after('deploy:symlink', 'deploy:cache_accelerator_clear');
set('opcache_webroot', 'public');
//set('public_url', 'https://yourshop.com');
//or after('deploy:symlink', 'deploy:opcache_reset');
