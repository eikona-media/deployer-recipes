<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/deploy/update_shared.php';
require_once __DIR__.'/build/composer.php';
require_once __DIR__.'/build/yarn.php';

/*
 * Akeneo Configuration
 */

// Akeneo shared files and dirs
set('shared_files', ['.env']);
set('shared_dirs', [
    'app/archive',
    'app/file_storage',
    'app/uploads',
    'var/file_storage',
    'var/logs',
    'var/sessions',
    'var/backups',
]);

// Akeneo writeable dirs
set('writable_dirs', [
    'app/archive',
    'app/file_storage',
    'app/uploads',
    'bin/backup',
    'var/cache',
    'var/file_storage',
    'var/logs',
    'var/sessions',
    'public/media',
]);

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
    // Run with custom Node.js Path for Node.js 10
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} install');
});

/*
 * Akeneo webpack compile (deploy)
 */
desc('Execute deploy:yarn_compile');
task('deploy:yarn_compile', function () {
    // Run with custom Node.js Path for Node.js 10
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run webpack');
});

/*
 * Doctrine schema update
 */
desc('Execute doctrine:schema:update:dump-sql');
task('doctrine:schema:update:dump-sql', function () {
    run('{{bin/php}} {{bin/console}} doctrine:schema:update --dump-sql {{console_options}}');
});

desc('Execute doctrine:schema:update:force');
task('doctrine:schema:update:force', function () {
    run('{{bin/php}} {{bin/console}} doctrine:schema:update --force {{console_options}}');
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
