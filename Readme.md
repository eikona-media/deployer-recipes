# Deployer Recipes

This repository contains third party recipes to integrate with [deployer](https://github.com/deployphp/deployer).

## Installing

~~~sh
composer require eikona-media/deployer-recipes --dev
~~~

Include recipes in `deploy.php` file.

```php
require 'recipe/contao_project.php';
```

## Recipes

| Recipe         |              Docs              |
|----------------|:------------------------------:|
| akeneo 6.*     |    [read](docs/akeneo6.md)     |
| contao project | [read](docs/contao_project.md) |
| cycon          |     [read](docs/cycon.md)      |
| shopware6      |   [read](docs/shopware6.md)    |

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

* https://deployer.org/docs/7.x/getting-started

* https://deployer.org/docs/7.x/recipe
