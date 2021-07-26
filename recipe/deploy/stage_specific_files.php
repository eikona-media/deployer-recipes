<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

desc('Update stage specific files');
task(
    'deploy:stage_specific_files',
    function () {

        $fileConfigs = get('stage_specific_files', []);
        if (!is_array($fileConfigs)) {
            writeln('<comment>Configuration "stage_specific_files" must be an array</comment>');
            return;
        }

        foreach ($fileConfigs as $fileConfig) {
            $fileSource = $fileConfig['source']; // .env.ci_{{stage}}
            $fileTarget = $fileConfig['target']; // .env
            $fileDelete = $fileConfig['delete']; // .env.ci_*

            $sudo = get('clear_use_sudo') ? 'sudo' : '';
            run("$sudo cp -rv {{release_path}}/$fileSource {{release_path}}/$fileTarget");

            if (!empty($fileDelete)) {
                run("$sudo rm -rf {{release_path}}/$fileDelete");
            }
        }
    }
)->setPrivate();