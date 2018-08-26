<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/contao.php';
require_once __DIR__.'/gitlab_ci.php';

/*
 * Deploy via gitlab-ci
 */
set('gitlab_ci_parameters_file', 'app/config/parameters.yml');

// Symfony exclude dirs for upload
add(
    'exclude_dirs',
    [
        'app/config/parameters.*',
        'tests',
        'var',
        'web/bundles',
        'web/*dev.php',
    ]
);

// Contao exclude dirs for upload
add(
    'exclude_dirs',
    [
        'web/assets',
        'web/files',
        'web/share',
        'web/system',
    ]
);

desc('Update shared dirs from repo');
task(
    'contao:update_shared',
    function () {
        $sharedPath = '{{deploy_path}}/shared';

        $dir = 'files';
        if (test("[ -d $(echo {{release_path}}/$dir) ]")) {
            run("cp -rv {{release_path}}/$dir $sharedPath/".\dirname(parse($dir)));
        }

        $dir = 'templates';
        if (test("[ -d $(echo {{release_path}}/$dir) ]")) {
            run("cp -rv {{release_path}}/$dir $sharedPath/".\dirname(parse($dir)));
        }
    }
)->setPrivate();
before('deploy:shared', 'contao:update_shared');
