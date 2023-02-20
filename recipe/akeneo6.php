<?php
declare(strict_types=1);

namespace Deployer;

$composerHome = getenv('COMPOSER_HOME') ?: getenv('HOME') . '/.composer';
include $composerHome . '/vendor/autoload.php';

require_once 'recipe/symfony4.php';
require_once 'recipe/yarn.php';
require_once 'recipe/deploy/gitlab_ci.php';
require_once 'recipe/deploy/supervisor.php';
require_once 'recipe/deploy/stage_specific_files.php';
require_once 'recipe/build/composer.php';
require_once 'recipe/build/yarn.php';

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

/*
 * Deploy Configuration
 */
set('default_timeout', 900);
set('keep_releases', 2);

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
before('deploy:shared', 'deploy:stage_specific_files');

/*
 * Build Configuration
 */
set('build_composer_options_extra', '--ignore-platform-reqs');
set('deploy_nodejs_path', '/opt/plesk/node/16/bin/');

/*
* Tasks
*/
desc('Execute pim:installer:assets');
task('pim:installer:assets', function () {
    run('{{bin/php}} {{bin/console}} pim:installer:assets --symlink --clean {{console_options}}');
});

desc('Execute pim:installer:dump-require-paths');
task('pim:installer:dump-require-paths', function () {
    run('{{bin/php}} {{bin/console}} pim:installer:dump-require-paths');
});

desc('Execute deploy:yarn_install');
task('deploy:yarn_install', function () {
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} install');
});

desc('Execute deploy:yarn_compile');
task('deploy:yarn_compile', function () {
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} packages:build');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run webpack');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run less');
    run('cd {{release_path}} && PATH="{{deploy_nodejs_path}}:$PATH" {{bin/yarn}} run update-extensions');
});

desc('Build your project');
task('build', [
    'build:composer',
    'build:yarn',
]);

after('deploy:vendors', 'pim:installer:assets');
after('pim:installer:assets', 'pim:installer:dump-require-paths');
after('pim:installer:dump-require-paths', 'deploy:yarn_install');
after('deploy:yarn_install', 'deploy:yarn_compile');
