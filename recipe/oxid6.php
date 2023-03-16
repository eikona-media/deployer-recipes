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

require_once __DIR__ . '/deploy/cache.php';
require_once __DIR__ . '/deploy/cleanup.php';
require_once __DIR__ . '/deploy/update_shared.php';
require_once __DIR__ . '/build/composer.php';

set('oxid_shop_ids', [1]);

/*
 * Oxid Configuration
 */

// Oxid shared files and dirs
set('shared_files', [
    'source/config.inc.php',
    'source/sitemap.xml',
    'source/robots.txt',
    'source/.htaccess',
]);
set('shared_dirs', [
    'source/out/pictures',
    'source/out/media',
    'source/out/downloads',
    'source/export',
    'source/log'
]);

// Shopware writeable dirs
set('writable_dirs', [
    'source/cache',
    'source/tmp',
    'source/out/pictures',
    'source/out/media',
    'source/out/downloads',
    'source/export',
    'source/log',
    'var',
]);

set('update_shared_dirs', [
    'source/out'
]);

set('update_shared_parameters', 'source/config.inc.php');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:shared',
    'deploy:vendors',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');

/*
 * Oxid build
 */

// Overwrite composer install. Add deploy-path....
task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        warning('To speed up composer installation setup "unzip" command with PHP zip extension.');
    }
    run('cd {{release_or_current_path}} && {{bin/composer}} {{composer_action}} {{composer_options}} 2>&1', [
        'env' => [
            'DEPLOY_PATH' => get('release_path'),
        ],
    ]);
});

// Copy shop config files (multishop)
task('oxid:set-module-settings', function () {
    $shops = get('oxid_shop_ids');
    if (!empty($shops) && is_array($shops)) {
        $stage = input()->hasArgument('stage') ? input()->getArgument('stage') : '';
        $sudo = get('clear_use_sudo') ? 'sudo' : '';
        run("$sudo mkdir -p {{release_path}}/var/configuration/shops");
        run("$sudo rm -rf {{release_path}}/var/configuration/shops/*");
        foreach($shops as $shop) {
            $sourcepath = "{{release_path}}/var/configuration/shops_{$stage}/{$shop}.yaml";
            $targetpath = "{{release_path}}/var/configuration/shops/{$shop}.yaml";
            if (test("[ -f {$sourcepath} ]")) {
                run("$sudo mv -v {$sourcepath} {$targetpath}");
            }
        }
        run("$sudo rm -R {{release_path}}/var/configuration/shops_*");
    }
})->desc('Copy shop configuration files for current stage.');;
after('deploy:vendors', 'oxid:set-module-settings');

// Load / apply oxid configuration
task('oxid:apply-config', function () {
    run('{{bin/php}} {{release_path}}/vendor/bin/oe-console oe:module:apply-configuration', [
        'env' => [
            'DEPLOY_PATH' => get('release_path'),
        ]
    ]);
})->desc('Apply shop configuration');
after('oxid:set-module-settings', 'oxid:apply-config');

// Oxid DB Migrations & View Generation....
task('oxid:db-updates', function () {
    run('{{bin/php}} {{release_path}}/vendor/bin/oe-eshop-db_migrate migrations:migrate', [
        'env' => [
            'DEPLOY_PATH' => get('release_path'),
        ]
    ]);
    run('{{bin/php}} {{release_path}}/vendor/bin/oe-eshop-db_views_generate', [
        'env' => [
            'DEPLOY_PATH' => get('release_path'),
        ]
    ]);
})->desc('Apply shop db updates');
after('oxid:apply-config', 'oxid:db-updates');

// optionally add to deploy.php:
//after('deploy:symlink', 'deploy:cache_status_clear');
//set('opcache_webroot', 'current');
//set('public_url', 'https://youroxid.com');
//after('deploy:symlink', 'deploy:opcache_reset');
