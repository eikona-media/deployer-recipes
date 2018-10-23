<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

use Deployer\Host\Localhost;
use Deployer\Ssh\Arguments;
use Deployer\Task\Context;

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
            $sshArguments = new Arguments();
            if ($host->getPort()) {
                $sshArguments = $sshArguments->withFlag('-P', $host->getPort());
            }
            if ($host->getConfigFile()) {
                $sshArguments = $sshArguments->withFlag('-F', $host->getConfigFile());
            }
            if ($host->getIdentityFile()) {
                $sshArguments = $sshArguments->withFlag('-i', $host->getIdentityFile());
            }
            if ($host->isForwardAgent()) {
                $sshArguments = $sshArguments->withFlag('-o', '"ForwardAgent yes"');
            }

            if (false === empty($sshArguments)) {
                if (!isset($config['options']) || !\is_array($config['options'])) {
                    $config['options'] = [];
                }
                $config['options'][] = $sshArguments;
            }

            runLocally(
                '{{local/bin/scp}} -rv '.implode(' ', $config['options'])." $source $host:$destination",
                $config
            );
        }
    }
)->setPrivate();

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
)->setPrivate();
