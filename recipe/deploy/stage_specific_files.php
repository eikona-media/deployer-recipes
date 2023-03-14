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
            $stage = get('labels', [])['stage'] ?? '';
            $fileSource = str_replace('{{stage}}', $stage, $fileConfig['source']); // .env.ci_{{stage}}
            $fileTarget = $fileConfig['target']; // .env
            $fileDelete = $fileConfig['delete']; // .env.ci_*

            $sudo = get('clear_use_sudo') ? 'sudo' : '';
            run("$sudo cp -rv {{release_path}}/$fileSource {{release_path}}/$fileTarget");

            if (!empty($fileDelete)) {
                if (is_string($fileDelete)) {
                    run("$sudo rm -rf {{release_path}}/$fileDelete");
                } elseif (is_array($fileDelete)) {
                    foreach ($fileDelete as $fileDeleteItem) {
                        run("$sudo rm -rf {{release_path}}/$fileDeleteItem");
                    }
                }
            }
        }
    }
)->hidden();
