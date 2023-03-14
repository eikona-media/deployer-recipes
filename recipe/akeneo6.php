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

require_once __DIR__.'/deploy/supervisor.php';
require_once __DIR__.'/deploy/stage_specific_files.php';
require_once __DIR__.'/build/composer.php';
require_once __DIR__.'/build/yarn.php';

/*
 * Akeneo Configuration
 */

// Akeneo shared files and dirs
set('shared_files', []);
set('shared_dirs', [
    'app/archive',
    'app/file_storage',
    'app/uploads',
    'var/logs',
    'var/sessions',
    'var/backups',
    'var/file_storage',
]);

// Akeneo writeable dirs
set('writable_dirs', [
    'app/archive',
    'app/file_storage',
    'app/uploads',
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

set('stage_specific_files', [
    [
        'source' => '.env.ci_{{stage}}',
        'target' => '.env',
        'delete' => '.env.*'
    ]
]);
// optionally add to deploy.php:
//before('deploy:shared', 'deploy:stage_specific_files');

set('build_composer_options_extra', '--ignore-platform-reqs');
set('deploy_nodejs_path', '/opt/plesk/node/16/bin/');

/*
 * Akeneo install assets
 */
desc('Execute pim:installer:assets');
task('pim:installer:assets', function () {
    run('{{bin/php}} {{bin/console}} pim:installer:assets --symlink --clean {{console_options}}');
});

/*
 * Akeneo dump required paths
 */
desc('Execute pim:installer:dump-require-paths');
task('pim:installer:dump-require-paths', function () {
    run('{{bin/php}} {{bin/console}} pim:installer:dump-require-paths');
});

/*
 * Yarn install (deploy)
 */
desc('Execute deploy:yarn_install');
task('deploy:yarn_install', function () {
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} install');
});

/*
 * Akeneo webpack & less compile (deploy)
 */
desc('Execute deploy:yarn_compile');
task('deploy:yarn_compile', function () {
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} packages:build');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run webpack');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run less');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run update-extensions');
});

/*
 * Akeneo build
 */
desc('Build your project');
task('build', [
    'build:composer',
    'build:yarn',
]);

// Symfony exclude paths for upload
add(
    'exclude_paths',
    [
        './tests',
        './var/cache',
        './var/logs',
        './public/bundles',
    ]
);

// Akeneo exclude paths for upload
add(
    'exclude_paths',
    [
        './var/bootstrap.php.cache',
    ]
);

// Tasks
after('deploy:vendors', 'pim:installer:assets');
after('pim:installer:assets', 'pim:installer:dump-require-paths');
after('pim:installer:dump-require-paths', 'deploy:yarn_install');
after('deploy:yarn_install', 'deploy:yarn_compile');
