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

set('tar_source', './');
set('tar_destination', '{{release_path}}/');

set(
    'local/bin/tar',
    function () {
        return runLocally('which tar');
    }
);

set(
    'tar_bin_host',
    function () {
        return which('tar');
    }
);

set(
    'tar_file_local',
    tempnam(sys_get_temp_dir(), 'deployer')
);
set(
    'tar_file_host',
    function () {
        return get('deploy_path').'/'.basename(get('tar_file_local'));
    }
);

add(
    'exclude_paths',
    [
        '.editorconfig',
        '.gitignore',
        'README.md',
        '.idea',
        '.php_cs.dist',
        './.lando*.yml',
        './.lando',
        './.project.conf',
        './deploy.yml',
        './deploy.yaml',
        './ecs.php',
        './ecs_template.php',
        './tools/ecs/*',
    ]
);

set('tar_create_options', 'cfzp');
set('tar_extract_options', 'xfzop');

desc('Creating tar file locally');
task('tar:create', function () {
    $excludesDefault = ['.git', '.gitignore', '.gitmodules', './deploy.php'];
    $excludes = \is_array(get('exclude_paths')) ? get('exclude_paths') : [];

    $excludes = array_unique(array_merge($excludesDefault, array_filter($excludes)));
    foreach ($excludes as &$exclude) {
        $exclude = '--exclude="'.$exclude.'"';
    }
    unset($exclude);

    $excludes = implode(' ', $excludes);
    $source = parse(get('tar_source'));

    runLocally("{{local/bin/tar}} {{tar_create_options}} {{tar_file_local}} $excludes $source");
})->hidden();

desc('Extracting tar file on host');
task('tar:extract', function () {
    $destination = parse(get('tar_destination'));
    run("{{tar_bin_host}} {{tar_extract_options}} {{tar_file_host}} -C $destination");
})->hidden();

desc('Removing tar files');
task('tar:cleanup', function () {
    $sudo = get('clear_use_sudo') ? 'sudo' : '';
    run("$sudo rm -rf {{tar_file_host}}");
    runLocally("$sudo rm -rf {{tar_file_local}}");
})->hidden();
