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

require_once 'recipe/symfony.php';
require_once 'contrib/yarn.php';
require_once __DIR__.'/deploy/supervisor.php';
require_once __DIR__.'/build/composer.php';
require_once __DIR__.'/build/yarn.php';

/*
 * Akeneo Configuration
 */

// Akeneo shared files and dirs
set('shared_files', []);
set('shared_dirs', [
    'var/logs',
    'var/sessions',
    'var/backups',
    'var/file_storage',
    'var/file_storage_local'
]);

// Akeneo writeable dirs
set('writable_dirs', [
    'bin/backup',
    'var/cache',
    'var/logs',
    'var/sessions',
    'var/file_storage',
    'public/media',
]);

set(
    'composer_options',
    '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest --ignore-platform-reqs'
);

set('build_composer_options_extra', '--ignore-platform-reqs');
set('deploy_nodejs_path', '/opt/plesk/node/18/bin/');

/*
 * Akeneo install assets
 */
desc('Execute pim:installer:assets');
task('pim:installer:assets', function () {
    run('{{bin/console}} pim:installer:assets --symlink --clean {{console_options}}');
})->hidden();

/*
 * Akeneo dump required paths
 */
desc('Execute pim:installer:dump-require-paths');
task('pim:installer:dump-require-paths', function () {
    run('{{bin/console}} pim:installer:dump-require-paths');
})->hidden();

/*
 * Yarn install (deploy)
 */
desc('Execute deploy:yarn_install');
task('deploy:yarn_install', function () {
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} install');
})->hidden();

/*
 * Akeneo webpack & less compile (deploy)
 */
desc('Execute deploy:yarn_compile');
task('deploy:yarn_compile', function () {
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} packages:build');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run webpack');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run less');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run update-extensions');
})->hidden();

/*
 * Akeneo build
 */
desc('Build your project');
task('build', [
    'build:composer',
    'build:yarn',
]);

// Exclude paths for upload
add(
    'exclude_paths',
    [
        './tests',
        './var/cache',
        './var/logs',
        './public/bundles',
        './var/bootstrap.php.cache',
    ]
);

// Tasks
after('deploy:vendors', 'pim:installer:assets');
after('pim:installer:assets', 'pim:installer:dump-require-paths');
after('pim:installer:dump-require-paths', 'deploy:yarn_install');
after('deploy:yarn_install', 'deploy:yarn_compile');
