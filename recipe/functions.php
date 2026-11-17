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

if (!\function_exists('Deployer\eikonaRunLocally')) {
    /**
     * runLocally() with a v7 style options array, working with Deployer 7 and 8.
     *
     * Deployer 8 removed the $options array of run()/runLocally() and renamed some options.
     * The recipes keep their array based configs (e.g. build_composer_run_options) and map them here.
     *
     * Supported keys (v7 or v8 spelling): timeout, idle_timeout|idleTimeout, env, cwd, shell,
     * no_throw|nothrow, real_time_output|forceOutput. Unknown keys (e.g. 'options' of scp_upload_config) are ignored.
     *
     * Note: for runLocally() 'timeout' => null means "no timeout" in Deployer 8.
     */
    function eikonaRunLocally(string $command, array $options = []): string
    {
        if (!eikonaIsDeployer8()) {
            return runLocally($command, $options);
        }

        $map = [
            'timeout' => 'timeout',
            'idle_timeout' => 'idleTimeout',
            'idleTimeout' => 'idleTimeout',
            'env' => 'env',
            'cwd' => 'cwd',
            'shell' => 'shell',
            'no_throw' => 'nothrow',
            'nothrow' => 'nothrow',
            'real_time_output' => 'forceOutput',
            'forceOutput' => 'forceOutput',
        ];

        $arguments = [];
        foreach ($options as $key => $value) {
            if (isset($map[$key])) {
                $arguments[$map[$key]] = $value;
            }
        }

        return runLocally($command, ...$arguments);
    }

    /**
     * Deployer 8 has no $options parameter in runLocally() any more.
     */
    function eikonaIsDeployer8(): bool
    {
        static $isDeployer8 = null;

        if (null === $isDeployer8) {
            $parameters = (new \ReflectionFunction('Deployer\runLocally'))->getParameters();
            $isDeployer8 = !isset($parameters[1]) || 'options' !== $parameters[1]->getName();
        }

        return $isDeployer8;
    }
}
