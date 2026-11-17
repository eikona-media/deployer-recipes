# Deployer Recipes for Deployer 8 (and 7)

This repository contains third party recipes to integrate with [deployer](https://github.com/deployphp/deployer).

Version 3.x supports **Deployer 8** (requires PHP >= 8.3) and Deployer 7 - see [Compatibility](#compatibility).

## Installing

~~~sh
composer require eikona-media/deployer-recipes:^3.0 --dev
~~~

With Deployer 8 installed globally (e.g. in a CI image):

~~~sh
composer global require deployer/deployer:^8.0 eikona-media/deployer-recipes:^3.0
~~~

Include recipes in `deploy.php` file.

```php
require 'recipe/contao_project.php';
```

## Compatibility

| Branch / Version | Deployer | PHP    |
|------------------|----------|--------|
| 1.x              | 6.x      | >= 7.4 |
| 2.x              | 7.x      | >= 8.1 |
| 3.x              | 7.x, 8.x | >= 8.1 (Deployer 8: >= 8.3) |

### Upgrade 2.x → 3.x

3.x works with Deployer 7 **and** 8, so projects can switch the recipes first and Deployer later.

* Deployer 8 removed the `$options` array of `run()`/`runLocally()`. The recipes keep their array based
  configs (`build_composer_run_options`, `build_npm_run_options`, `build_yarn_run_options`,
  `build_shopware_js_run_options`, `scp_upload_config`) and map them to named arguments via
  `eikonaRunLocally()` (`recipe/functions.php`). Supported keys: `timeout`, `idle_timeout`, `env`, `cwd`,
  `shell`, `no_throw`, `real_time_output`.
* `'timeout' => null` means "no timeout" for local commands in Deployer 8 as well.
  For remote `run()` calls in your own `deploy.php`, Deployer 8 uses `{{default_timeout}}` (300 s) when
  `timeout` is `null` - pass an explicit value for long running commands.
* In your own `deploy.php` use named arguments: `run('cmd', timeout: 1800, env: ['FOO' => 'bar'])`
  (works with Deployer 7 and 8).
* Deployer 8 adds the task `deploy:env` to `deploy:prepare`: copies `.env.example` to `.env` if no `.env` exists.
  `deploy:stage_specific_files` runs later and still overwrites `.env`.
* `recipe/shopware6.php` is deprecated: it already fails to load with Deployer 7 (`deploy:cache:warmup`)
  and builds the recovery folder removed in Shopware 6.5. Use Deployer's own `recipe/common.php` +
  [`shopware/deployment-helper`](https://developer.shopware.com/docs/guides/hosting/installation-updates/deployments/deployment-with-deployer.html)
  together with `recipe/deploy/gitlab_ci.php` and `recipe/deploy/stage_specific_files.php`.

## Recipes

| Recipe         |              Docs              |
|----------------|:------------------------------:|
| akeneo 7.*     |    [read](docs/akeneo7.md)     |
| akeneo 6.*     |    [read](docs/akeneo6.md)     |
| contao project | [read](docs/contao_project.md) |
| cycon          |     [read](docs/cycon.md)      |
| shopware6 (deprecated) | [read](docs/shopware6.md) |

## Build Helper

| Recipe   |              Docs              |
|----------|:------------------------------:|
| composer | [read](docs/build/composer.md) |
| npm      |   [read](docs/build/npm.md)    |
| yarn     |   [read](docs/build/yarn.md)   |

## Deploy Helper

| Recipe        |                 Docs                 |
|---------------|:------------------------------------:|
| cache         |     [read](docs/deploy/cache.md)     |
| cleanup       |    [read](docs/deploy/cleanup.md)    |
| gitlab_ci     |   [read](docs/deploy/gitlab_ci.md)   |
| no_releases   |  [read](docs/deploy/no_releases.md)  |
| scp           |      [read](docs/deploy/scp.md)      |
| supervisor    |  [read](docs/deploy/supervisor.md)   |
| tar           |      [read](docs/deploy/tar.md)      |
| update_shared | [read](docs/deploy/update_shared.md) |

## More Doc + Recipes...

* https://deployer.org/docs/8.x/getting-started

* https://deployer.org/docs/8.x/recipe
