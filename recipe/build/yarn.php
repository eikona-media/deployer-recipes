<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

set('local/bin/yarn', function () {
    return runLocally('which yarn');
});

set('build_yarn_path', './');
set('build_yarn_action', 'install');
set('build_yarn_options', '{{build_yarn_action}}');
set('build_yarn_run_options', ['timeout' => null]);

desc('Build yarn packages');
task('build:yarn', function () {
    runLocally('cd {{build_yarn_path}} && {{local/bin/yarn}} {{build_yarn_options}}', get('build_yarn_run_options'));
});
