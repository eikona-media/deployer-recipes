<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

set('local/bin/php', function () {
    return runLocally('which php');
});

set('local/bin/composer', function () {
    return runLocally('which composer');
});

set('build_composer_path', './');
set('build_composer_action', 'install');
set('build_composer_options_extra', '');
set('build_composer_options', '{{build_composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-scripts');
set('build_composer_run_options', ['timeout' => null]);

desc('Build composer packages');
task('build:composer', function () {
    runLocally('cd {{build_composer_path}} && {{local/bin/php}} {{local/bin/composer}} {{build_composer_options}} {{build_composer_options_extra}}', get('build_composer_run_options'));
});

set('build_composer_dump_autoload_action', 'dump-autoload');
set('build_composer_dump_autoload_options_extra', '');
set('build_composer_dump_autoload_options', '{{build_composer_dump_autoload_action}} -o');

desc('Dump composer autoload');
task('build:composer:dump-autoload', function () {
    runLocally('cd {{build_composer_path}} && {{local/bin/php}} {{local/bin/composer}} {{build_composer_dump_autoload_options}} {{build_composer_dump_autoload_options_extra}}', get('build_composer_run_options'));
});
