# Deployer Recipes

This repository contains third party recipes to integrate with [deployer](https://github.com/deployphp/deployer).

## Installing

~~~sh
composer require eikona-media/deployer-recipes --dev
~~~

Include recipes in `deploy.php` file.

```php
require 'recipe/contao.php';
```

## Recipes

| Recipe     | Docs                        |
| ---------- |:---------------------------:|
| akeneo 2.* | [read](docs/akeneo2.md)     |
| contao     | [read](docs/contao.md)      |
| cycon      | [read](docs/cycon.md)       |
| nextcloud  | [read](docs/nextcloud.md)   |
| roundcube  | [read](docs/roundcube.md)   |

## Build Helper

| Recipe         | Docs                                 |
|----------------|:------------------------------------:|
| composer       | [read](docs/build/composer.md)       |
| npm            | [read](docs/build/npm.md)            |
| yarn           | [read](docs/build/yarn.md)           |

## Deploy Helper

| Recipe         | Docs                                 |
|----------------|:------------------------------------:|
| tar            | [read](docs/deploy/tar.md)           |
| scp            | [read](docs/deploy/scp.md)           |
| gitlab_ci      | [read](docs/deploy/gitlab_ci.md)     |
| no_releases    | [read](docs/deploy/no_releases.md)   |
| update_shared  | [read](docs/deploy/update_shared.md) |
| supervisor     | [read](docs/deploy/supervisor.md)    |


## More Doc + Recipes...

* https://deployer.org/docs

* https://github.com/deployphp/recipes/
