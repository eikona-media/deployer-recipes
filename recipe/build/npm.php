<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

set('local/bin/npm', function () {
    return runLocally('which npm');
});

set('build_npm_path', './');
set('build_npm_action', 'install');
set('build_npm_options', '{{build_npm_action}}');

desc('Build npm packages');
task('build:npm', function () {
    runLocally('cd {{build_npm_path}} && {{local/bin/npm}} {{build_npm_options}}');
});
