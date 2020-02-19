<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

desc('Update shared dirs from repo');
task(
    'deploy:update_shared_dirs',
    function () {
        $sharedPath = '{{deploy_path}}/shared';

        foreach (array_filter(get('update_shared_dirs')) as $dir) {
            // Create shared dir if it does not exist.
            run("mkdir -p $sharedPath/$dir");

            // If release contains shared dir, copy that dir from release to shared.
            if (test("[ -d $(echo {{release_path}}/$dir) ]")) {
                run("cp -rv {{release_path}}/$dir $sharedPath/".\dirname(parse($dir)));
            }
        }
    }
)->setPrivate();

desc('Update shared parameters for stage from repo');
task(
    'deploy:update_shared_parameters',
    function () {
        if (get('update_shared_parameters_target') !== null) {
            $fileTarget = get('update_shared_parameters_target');
        } elseif (get('update_shared_parameters') !== null) {
            $fileTarget = get('update_shared_parameters');
            writeln(
                '<comment>Configuration "update_shared_parameters" is deprecated. Use "update_shared_parameters_target".</comment>'
            );
        }

        if (empty($fileTarget)) {
            writeln(
                '<comment>Configuration "update_shared_parameters_target" not set</comment>'
            );

            return;
        }

        $fileSource = get('update_shared_parameters_source');
        $fileDelete = get('update_shared_parameters_delete');

        if (empty($fileSource) || $fileDelete === null) {
            $filePieces = explode('.', $fileTarget);
            $prefix = implode('.', \array_slice($filePieces, 0, -1));
            $extension = end($filePieces);
            if (empty($fileSource)) {
                $fileSource = $prefix.'_{{stage}}.'.$extension;
            }
            if ($fileDelete === null) {
                $fileDelete = $prefix.'_*';
            }
        }

        if (!test("[ -f {{release_path}}/$fileSource ]") && isVerbose()) {
            writeln('<comment>Source parameters "'.$fileSource.'" for stage not found</comment>');

            return;
        }

        $sharedPath = '{{deploy_path}}/shared';
        $sudo = get('clear_use_sudo') ? 'sudo' : '';

        run("$sudo cp -rv {{release_path}}/$fileSource $sharedPath/$fileTarget");

        if (!empty($fileDelete)) {
            run("$sudo rm -rf {{release_path}}/$fileDelete");
        }
    }
)->setPrivate();
