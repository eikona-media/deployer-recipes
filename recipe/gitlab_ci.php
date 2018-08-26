<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/scp.php';

desc('Update parameters for stage');
task(
    'gitlab_ci:update_parameters',
    function () {
        if (empty($file = get('gitlab_ci_parameters_file')) && isVerbose()) {
            writeln('<comment>Configuration "gitlab_ci_parameters_file" not set</comment>');

            return;
        }

        list($prefix, $extension) = explode('.', $file);
        $fileStage = $prefix.'_{{stage}}.'.$extension;

        if (!test("[ -f {{release_path}}/$fileStage ]") && isVerbose()) {
            writeln('<comment>Parameters "'.$fileStage.'" for stage not found</comment>');

            return;
        }

        $sharedPath = '{{deploy_path}}/shared';
        $sudo = get('clear_use_sudo') ? 'sudo' : '';

        run("$sudo cp -rv {{release_path}}/$fileStage $sharedPath/$file");

        $filesStages = $prefix.'_*';
        run("$sudo rm -rf {{release_path}}/$filesStages");
    }
)->setPrivate();
after('deploy:shared', 'gitlab_ci:update_parameters');

add('exclude_dirs', ['.gitlab-ci.yml']);
desc('Upload code - git clone should be done by gitlab-ci');
task(
    'deploy:update_code',
    function () {
        set('scp_upload_source', './');
        set('scp_upload_destination', '{{release_path}}/');
        invoke('scp:upload:tar');
    }
);

after('deploy:failed', 'deploy:unlock');
