<?php

/*
 * This file is part of EIKONA deployer recipe.
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
        if (empty($file = get('update_shared_parameters')) && isVerbose()) {
            writeln('<comment>Configuration "update_shared_parameters" not set</comment>');

            return;
        }

        $filePieces = explode('.', $file);
        $prefix = implode('.', array_slice($filePieces, 0, -1));
        $extension = end( $filePieces );
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
