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

use Deployer\Host\Localhost;
use Deployer\Task\Context;
use function Deployer\Support\parse_home_dir;

require_once __DIR__.'/tar.php';

set('scp_upload_source', './');
set('scp_upload_destination', '{{release_path}}/');

set(
    'local/bin/scp',
    function () {
        return runLocally('which scp');
    }
);
set('scp_upload_config', []);

desc('Copying files to host');
task(
    'scp:upload',
    function () {
        $host = Context::get()->getHost();
        $source = parse(get('scp_upload_source'));
        $destination = parse(get('scp_upload_destination'));

        $defaults = [
            'options' => [],
        ];
        $config = array_merge($defaults, get('scp_upload_config'));

        if ($host instanceof Localhost) {
            runLocally('cp -rv '.implode(' ', $config['options'])." $source $destination", $config);
        } else {
            $options = $config['options'];
            if ($host->has('ssh_arguments')) {
                foreach ($host->getSshArguments() as $arg) {
                    $options = array_merge($options, explode(' ', $arg));
                }
            }
            if ($host->has('port')) {
                $options = array_merge($options, ['-P', $host->getPort()]);
            }
            if ($host->has('config_file')) {
                $options = array_merge($options, ['-F', parse_home_dir($host->getConfigFile())]);
            }
            if ($host->has('identity_file')) {
                $options = array_merge($options, ['-i', parse_home_dir($host->getIdentityFile())]);
            }
            if ($host->has('forward_agent') && $host->getForwardAgent()) {
                $options = array_merge($options, ['-A']);
            }

            runLocally(
                '{{local/bin/scp}} -rv '.implode(' ', $options)." $source {$host->connectionString()}:$destination",
                $config
            );
        }
    }
)->hidden();

desc('Copying files to host with tar');
task(
    'scp:upload:tar',
    function () {
        set('tar_source', get('scp_upload_source'));
        set('tar_destination', get('scp_upload_destination'));
        set('scp_upload_source', get('tar_file_local'));
        set('scp_upload_destination', get('tar_file_host'));
        invoke('tar:create');
        invoke('scp:upload');
        invoke('tar:extract');
        invoke('tar:cleanup');
    }
)->hidden();
